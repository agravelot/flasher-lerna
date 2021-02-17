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


Table: testimonials
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] email                                          VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 3] body                                           TEXT                 null: false  primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
[ 4] published_at                                   TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 5] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 6] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 7] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 8] sso_id                                         UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []


JSON Sample
-------------------------------------
{    "id": 7,    "name": "vCrhpkSUnixErcHdSmBLWHmgG",    "email": "InaCwchrZOXLBTVGiMkLIvQZP",    "body": "yOfIMjYIIeEKyePjKyAjwoQYl",    "published_at": "2186-09-08T14:21:54.313519588+02:00",    "user_id": 20,    "created_at": "2053-03-29T09:34:47.419863616+01:00",    "updated_at": "2144-06-14T13:09:13.467162856+02:00",    "sso_id": "muaZVtBwVjFuaUytwVKSWmPLx"}



*/

// Testimonials struct is a row record of the testimonials table in the flasher database
type Testimonials struct {
	//[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
	ID int32 `gorm:"primary_key;column:id;type:INT4;" json:"id"`
	//[ 1] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Name string `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	//[ 2] email                                          VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Email string `gorm:"column:email;type:VARCHAR;size:255;" json:"email"`
	//[ 3] body                                           TEXT                 null: false  primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
	Body string `gorm:"column:body;type:TEXT;" json:"body"`
	//[ 4] published_at                                   TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	PublishedAt null.Time `gorm:"column:published_at;type:TIMESTAMP;" json:"published_at"`
	//[ 5] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
	UserID null.Int `gorm:"column:user_id;type:INT8;" json:"user_id"`
	//[ 6] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	CreatedAt null.Time `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	//[ 7] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	UpdatedAt null.Time `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	//[ 8] sso_id                                         UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []
	SsoID null.String `gorm:"column:sso_id;type:UUID;" json:"sso_id"`
}

var testimonialsTableInfo = &TableInfo{
	Name: "testimonials",
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
			Name:               "email",
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
			GoFieldName:        "Email",
			GoFieldType:        "string",
			JSONFieldName:      "email",
			ProtobufFieldName:  "email",
			ProtobufType:       "string",
			ProtobufPos:        3,
		},

		&ColumnInfo{
			Index:              3,
			Name:               "body",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "TEXT",
			DatabaseTypePretty: "TEXT",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "TEXT",
			ColumnLength:       -1,
			GoFieldName:        "Body",
			GoFieldType:        "string",
			JSONFieldName:      "body",
			ProtobufFieldName:  "body",
			ProtobufType:       "string",
			ProtobufPos:        4,
		},

		&ColumnInfo{
			Index:              4,
			Name:               "published_at",
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
			GoFieldName:        "PublishedAt",
			GoFieldType:        "null.Time",
			JSONFieldName:      "published_at",
			ProtobufFieldName:  "published_at",
			ProtobufType:       "uint64",
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
func (t *Testimonials) TableName() string {
	return "testimonials"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (t *Testimonials) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (t *Testimonials) Prepare() {
}

// Validate invoked before performing action, return an error if field is not populated.
func (t *Testimonials) Validate(action Action) error {
	return nil
}

// TableInfo return table meta data
func (t *Testimonials) TableInfo() *TableInfo {
	return testimonialsTableInfo
}
