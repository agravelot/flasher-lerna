package storage

type Storage interface {
	New(uri string) (Storage, error)
	Begin() Storage
	Rollback() Storage
	Close() error
}
