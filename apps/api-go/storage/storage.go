package storage

import "api-go/config"

type Storage interface {
	New(c *config.Config) (Storage, error)
	Begin() Storage
	Rollback() Storage
	Close() error
}
