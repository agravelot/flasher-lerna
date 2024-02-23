package uploads

import (
	"encoding/json"
	"fmt"
	"github.com/kr/pretty"
	amqp "github.com/rabbitmq/amqp091-go"
	"log"
)

type Consumer struct {
	conn    *amqp.Connection
	channel *amqp.Channel
	tag     string
	done    chan error
}

type OnUploadedFileMessage func(file UploadedFile) error

func NewEventListener(onEvent OnUploadedFileMessage) error {
	// TODO extract
	ctag := ""
	amqpURI := "amqp://guest:guest@localhost:5672/"
	exchange := "s3-events"
	key := "#"
	queueName := "new_s3_events"

	c := &Consumer{
		conn:    nil,
		channel: nil,
		tag:     ctag,
		done:    make(chan error),
	}

	var err error

	config := amqp.Config{Properties: amqp.NewConnectionProperties()}
	config.Properties.SetClientConnectionName("sample-consumer")

	c.conn, err = amqp.DialConfig(amqpURI, config)
	if err != nil {
		return fmt.Errorf("error dial: %w", err)
	}

	go func() {
		log.Printf("closing: %s", <-c.conn.NotifyClose(make(chan *amqp.Error)))
	}()

	c.channel, err = c.conn.Channel()
	if err != nil {
		return fmt.Errorf("channel: %w", err)
	}

	queue, err := c.channel.QueueDeclare(
		queueName, // name of the queue
		true,      // durable
		false,     // delete when unused
		false,     // exclusive
		false,     // noWait
		nil,       // arguments
	)
	if err != nil {
		return fmt.Errorf("queue Declare: %w", err)
	}

	err = c.channel.QueueBind(
		queue.Name, // name of the queue
		key,        // bindingKey
		exchange,   // sourceExchange
		false,      // noWait
		nil,        // arguments
	)

	if err != nil {
		return fmt.Errorf("queue bind: %w", err)
	}

	deliveries, err := c.channel.Consume(
		queue.Name,
		c.tag,
		false,
		false,
		false,
		false,
		nil,
	)
	if err != nil {
		return fmt.Errorf("failed queue consume: %w", err)
	}

	go handle(deliveries, c.done, onEvent)

	return nil
}

func handle(deliveries <-chan amqp.Delivery, done chan error, onEvent OnUploadedFileMessage) {
	cleanup := func() {
		log.Printf("handle: deliveries channel closed\n")
		done <- nil
	}

	defer cleanup()

	log.Println("listening for upload messages")

	for delivery := range deliveries {

		var event UploadedFile
		err := json.Unmarshal(delivery.Body, &event)
		if err != nil {
			log.Printf("Error unmarshalling event: %v\n", err)
			err = delivery.Nack(false, false)
			if err != nil {
				pretty.Log(fmt.Errorf("failed to nack delivery: %w", err))
			}

			continue
		}

		err = onEvent(event)

		if err != nil {
			pretty.Log(fmt.Errorf("failed to onEvent: %w", err))
			err = delivery.Nack(false, false)
			if err != nil {
				pretty.Log(fmt.Errorf("failed to nack delivery: %w", err))
			}

			continue
		}

		err = delivery.Ack(false)
		if err != nil {
			pretty.Log(fmt.Errorf("failed to ack delivery: %w", err))
		}
	}
}
