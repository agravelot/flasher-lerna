package model

import (
	"database/sql"
	"time"

	"github.com/guregu/null"
	"github.com/satori/go.uuid"
)

var (
	_ = time.Second
	_ = sql.LevelDefault
	_ = null.Bool{}
	_ = uuid.UUID{}
)

/*
DB Table Details
-------------------------------------


Table: migrations
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] migration                                      VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] batch                                          INT4                 null: false  primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []


JSON Sample
-------------------------------------
{    "id": 94,    "migration": "LCfntrZIymQHRDyxdFQmVAhKm",    "batch": 95}



*/

// Migrations struct is a row record of the migrations table in the flasher database
type Migrations struct {
	//[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
	ID int32 `gorm:"primary_key;column:id;type:INT4;" json:"id"`
	//[ 1] migration                                      VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Migration string `gorm:"column:migration;type:VARCHAR;size:255;" json:"migration"`
	//[ 2] batch                                          INT4                 null: false  primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []
	Batch int32 `gorm:"column:batch;type:INT4;" json:"batch"`
}

var migrationsTableInfo = &TableInfo{
	Name: "migrations",
	Columns: []*ColumnInfo{

		&ColumnInfo{
			Index:              0,
			Name:               "id",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "INT4",
			DatabaseTypePretty: "INT4",
			IsPrimaryKey:       true,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "INT4",
			ColumnLength:       -1,
			GoFieldName:        "ID",
			GoFieldType:        "int32",
			JSONFieldName:      "id",
			ProtobufFieldName:  "id",
			ProtobufType:       "int32",
			ProtobufPos:        1,
		},

		&ColumnInfo{
			Index:              1,
			Name:               "migration",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "VARCHAR",
			DatabaseTypePretty: "VARCHAR(255)",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "VARCHAR",
			ColumnLength:       255,
			GoFieldName:        "Migration",
			GoFieldType:        "string",
			JSONFieldName:      "migration",
			ProtobufFieldName:  "migration",
			ProtobufType:       "string",
			ProtobufPos:        2,
		},

		&ColumnInfo{
			Index:              2,
			Name:               "batch",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "INT4",
			DatabaseTypePretty: "INT4",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "INT4",
			ColumnLength:       -1,
			GoFieldName:        "Batch",
			GoFieldType:        "int32",
			JSONFieldName:      "batch",
			ProtobufFieldName:  "batch",
			ProtobufType:       "int32",
			ProtobufPos:        3,
		},
	},
}

// TableName sets the insert table name for this struct type
func (m *Migrations) TableName() string {
	return "migrations"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (m *Migrations) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (m *Migrations) Prepare() {
}

// Validate invoked before performing action, return an error if field is not populated.
func (m *Migrations) Validate(action Action) error {
	return nil
}

// TableInfo return table meta data
func (m *Migrations) TableInfo() *TableInfo {
	return migrationsTableInfo
}
