package model

import (
	"database/sql/driver"
	"encoding/json"
	"errors"
	"fmt"

	"github.com/lib/pq"
)

type CustomProperties struct {
	Height int `json:"height"`
	Width  int `json:"width"`
}

func (ri CustomProperties) Value() (driver.Value, error) {
	return nil, nil
}

func (ri *CustomProperties) Scan(value interface{}) error {
	bytes, ok := value.([]byte)
	if !ok {
		return errors.New(fmt.Sprint("Failed to unmarshal JSONB value:", value))
	}

	result := CustomProperties{}
	err := json.Unmarshal(bytes, &result)
	*ri = result
	return err
}

type ResponsiveImages struct {
	Responsive Responsive `gorm:"" json:"responsive"`
}

func (ri ResponsiveImages) Value() (driver.Value, error) {
	return nil, nil
}

func (ri *ResponsiveImages) Scan(value interface{}) error {
	bytes, ok := value.([]byte)
	if !ok {
		return errors.New(fmt.Sprint("Failed to unmarshal JSONB value:", value))
	}

	result := ResponsiveImages{}
	err := json.Unmarshal(bytes, &result)
	*ri = result
	return err
}

type Responsive struct {
	Urls      pq.StringArray `gorm:"type:text[];" json:"urls"`
	Base64Svg string         `json:"base64svg"`
}

type GeneratedConversions struct {
	Thumb      bool `json:"thumb"`
	Responsive bool `json:"responsive"`
}
