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


Table: cosplayers
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] slug                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 3] description                                    TEXT                 null: true   primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
[ 4] picture                                        VARCHAR(255)         null: true   primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 5] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 6] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 7] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 8] sso_id                                         UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []


JSON Sample
-------------------------------------
{    "id": 26,    "name": "ncGFMAYyYDyfrFvvMhGcuanRQ",    "slug": "ewNiqjxtpEUjUXjfOBOFKdEfa",    "description": "kyaFfMyZUDArOOMXsRWneHcAF",    "picture": "oBvSllELSlndsqDLSVpGtuCUR",    "user_id": 51,    "created_at": "2110-02-27T20:23:13.277401081+01:00",    "updated_at": "2187-07-15T17:57:26.484645151+02:00",    "sso_id": "iiDyFYXCdvRyElGoFFtZbSKXj"}



*/

// Cosplayers struct is a row record of the cosplayers table in the flasher database
type Cosplayers struct {
	//[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
	ID int32 `gorm:"primary_key;column:id;type:INT4;" json:"id"`
	//[ 1] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Name string `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	//[ 2] slug                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Slug string `gorm:"column:slug;type:VARCHAR;size:255;" json:"slug"`
	//[ 3] description                                    TEXT                 null: true   primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
	Description null.String `gorm:"column:description;type:TEXT;" json:"description"`
	//[ 4] picture                                        VARCHAR(255)         null: true   primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Picture null.String `gorm:"column:picture;type:VARCHAR;size:255;" json:"picture"`
	//[ 5] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
	UserID null.Int `gorm:"column:user_id;type:INT8;" json:"user_id"`
	//[ 6] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	CreatedAt null.Time `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	//[ 7] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	UpdatedAt null.Time `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	//[ 8] sso_id                                         UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []
	SsoID null.String `gorm:"column:sso_id;type:UUID;" json:"sso_id"`
}

var cosplayersTableInfo = &TableInfo{
	Name: "cosplayers",
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
			Name:               "name",
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
			GoFieldName:        "Name",
			GoFieldType:        "string",
			JSONFieldName:      "name",
			ProtobufFieldName:  "name",
			ProtobufType:       "string",
			ProtobufPos:        2,
		},

		&ColumnInfo{
			Index:              2,
			Name:               "slug",
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
			GoFieldName:        "Slug",
			GoFieldType:        "string",
			JSONFieldName:      "slug",
			ProtobufFieldName:  "slug",
			ProtobufType:       "string",
			ProtobufPos:        3,
		},

		&ColumnInfo{
			Index:              3,
			Name:               "description",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "TEXT",
			DatabaseTypePretty: "TEXT",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "TEXT",
			ColumnLength:       -1,
			GoFieldName:        "Description",
			GoFieldType:        "null.String",
			JSONFieldName:      "description",
			ProtobufFieldName:  "description",
			ProtobufType:       "string",
			ProtobufPos:        4,
		},

		&ColumnInfo{
			Index:              4,
			Name:               "picture",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "VARCHAR",
			DatabaseTypePretty: "VARCHAR(255)",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "VARCHAR",
			ColumnLength:       255,
			GoFieldName:        "Picture",
			GoFieldType:        "null.String",
			JSONFieldName:      "picture",
			ProtobufFieldName:  "picture",
			ProtobufType:       "string",
			ProtobufPos:        5,
		},

		&ColumnInfo{
			Index:              5,
			Name:               "user_id",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "INT8",
			DatabaseTypePretty: "INT8",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "INT8",
			ColumnLength:       -1,
			GoFieldName:        "UserID",
			GoFieldType:        "null.Int",
			JSONFieldName:      "user_id",
			ProtobufFieldName:  "user_id",
			ProtobufType:       "int32",
			ProtobufPos:        6,
		},

		&ColumnInfo{
			Index:              6,
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
			ProtobufPos:        7,
		},

		&ColumnInfo{
			Index:              7,
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
			ProtobufPos:        8,
		},

		&ColumnInfo{
			Index:              8,
			Name:               "sso_id",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "UUID",
			DatabaseTypePretty: "UUID",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "UUID",
			ColumnLength:       -1,
			GoFieldName:        "SsoID",
			GoFieldType:        "null.String",
			JSONFieldName:      "sso_id",
			ProtobufFieldName:  "sso_id",
			ProtobufType:       "string",
			ProtobufPos:        9,
		},
	},
}

// TableName sets the insert table name for this struct type
func (c *Cosplayers) TableName() string {
	return "cosplayers"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (c *Cosplayers) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (c *Cosplayers) Prepare() {
}

// Validate invoked before performing action, return an error if field is not populated.
func (c *Cosplayers) Validate(action Action) error {
	return nil
}

// TableInfo return table meta data
func (c *Cosplayers) TableInfo() *TableInfo {
	return cosplayersTableInfo
}
