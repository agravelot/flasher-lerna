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


Table: categorizables
[ 0] category_id                                    INT4                 null: false  primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] categorizable_type                             VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] categorizable_id                               INT8                 null: false  primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 3] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 4] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []


JSON Sample
-------------------------------------
{    "category_id": 3,    "categorizable_type": "kttcYCsDvPxgnOWaYSRVFcKlA",    "categorizable_id": 23,    "created_at": "2085-03-17T23:22:50.691477524+01:00",    "updated_at": "2194-09-05T09:56:31.214852072+02:00"}


Comments
-------------------------------------
[ 0] Warning table: categorizables does not have a primary key defined, setting col position 1 category_id as primary key




*/

// Categorizables struct is a row record of the categorizables table in the flasher database
type Categorizables struct {
	//[ 0] category_id                                    INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
	CategoryID int32 `gorm:"primary_key;column:category_id;type:INT4;" json:"category_id"`
	//[ 1] categorizable_type                             VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	CategorizableType string `gorm:"column:categorizable_type;type:VARCHAR;size:255;" json:"categorizable_type"`
	//[ 2] categorizable_id                               INT8                 null: false  primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
	CategorizableID int64 `gorm:"column:categorizable_id;type:INT8;" json:"categorizable_id"`
	//[ 3] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	CreatedAt null.Time `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	//[ 4] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	UpdatedAt null.Time `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
}

var categorizablesTableInfo = &TableInfo{
	Name: "categorizables",
	Columns: []*ColumnInfo{

		&ColumnInfo{
			Index:   0,
			Name:    "category_id",
			Comment: ``,
			Notes: `Warning table: categorizables does not have a primary key defined, setting col position 1 category_id as primary key
`,
			Nullable:           false,
			DatabaseTypeName:   "INT4",
			DatabaseTypePretty: "INT4",
			IsPrimaryKey:       true,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "INT4",
			ColumnLength:       -1,
			GoFieldName:        "CategoryID",
			GoFieldType:        "int32",
			JSONFieldName:      "category_id",
			ProtobufFieldName:  "category_id",
			ProtobufType:       "int32",
			ProtobufPos:        1,
		},

		&ColumnInfo{
			Index:              1,
			Name:               "categorizable_type",
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
			GoFieldName:        "CategorizableType",
			GoFieldType:        "string",
			JSONFieldName:      "categorizable_type",
			ProtobufFieldName:  "categorizable_type",
			ProtobufType:       "string",
			ProtobufPos:        2,
		},

		&ColumnInfo{
			Index:              2,
			Name:               "categorizable_id",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "INT8",
			DatabaseTypePretty: "INT8",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "INT8",
			ColumnLength:       -1,
			GoFieldName:        "CategorizableID",
			GoFieldType:        "int64",
			JSONFieldName:      "categorizable_id",
			ProtobufFieldName:  "categorizable_id",
			ProtobufType:       "int32",
			ProtobufPos:        3,
		},

		&ColumnInfo{
			Index:              3,
			Name:               "created_at",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "TIMESTAMP",
			DatabaseTypePretty: "TIMESTAMP",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "TIMESTAMP",
			ColumnLength:       -1,
			GoFieldName:        "CreatedAt",
			GoFieldType:        "null.Time",
			JSONFieldName:      "created_at",
			ProtobufFieldName:  "created_at",
			ProtobufType:       "uint64",
			ProtobufPos:        4,
		},

		&ColumnInfo{
			Index:              4,
			Name:               "updated_at",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "TIMESTAMP",
			DatabaseTypePretty: "TIMESTAMP",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "TIMESTAMP",
			ColumnLength:       -1,
			GoFieldName:        "UpdatedAt",
			GoFieldType:        "null.Time",
			JSONFieldName:      "updated_at",
			ProtobufFieldName:  "updated_at",
			ProtobufType:       "uint64",
			ProtobufPos:        5,
		},
	},
}

// TableName sets the insert table name for this struct type
func (c *Categorizables) TableName() string {
	return "categorizables"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (c *Categorizables) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (c *Categorizables) Prepare() {
}

// Validate invoked before performing action, return an error if field is not populated.
func (c *Categorizables) Validate(action Action) error {
	return nil
}

// TableInfo return table meta data
func (c *Categorizables) TableInfo() *TableInfo {
	return categorizablesTableInfo
}
