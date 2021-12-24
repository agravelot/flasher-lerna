package model

import (
	"database/sql"
	"time"

	"github.com/guregu/null"
	uuid "github.com/satori/go.uuid"
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


Table: comments
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 2] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 3] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 4] commentable_id                                 INT4                 null: false  primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []
[ 5] commentable_type                               VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 6] body                                           TEXT                 null: false  primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
[ 7] sso_id                                         UUID                 null: false  primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []


JSON Sample
-------------------------------------
{    "id": 62,    "created_at": "2278-08-07T07:37:24.740339568+02:00",    "updated_at": "2203-03-11T01:37:26.83867933+01:00",    "user_id": 83,    "commentable_id": 14,    "commentable_type": "qUVkmRNLHoirwZhpSJphgvCLZ",    "body": "JsqiGEtsWkJYJQeNhgVsVaKMi",    "sso_id": "fMGMMgQywuBchFhHtoFuCbxHy"}



*/

// Comments struct is a row record of the comments table in the flasher database
type Comments struct {
	//[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
	ID int32 `gorm:"primary_key;column:id;type:INT4;" json:"id"`
	//[ 1] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	CreatedAt sql.NullTime `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	//[ 2] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	UpdatedAt sql.NullTime `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	//[ 3] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
	UserID null.Int `gorm:"column:user_id;type:INT8;" json:"user_id"`
	//[ 4] commentable_id                                 INT4                 null: false  primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []
	CommentableID int32 `gorm:"column:commentable_id;type:INT4;" json:"commentable_id"`
	//[ 5] commentable_type                               VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	CommentableType string `gorm:"column:commentable_type;type:VARCHAR;size:255;" json:"commentable_type"`
	//[ 6] body                                           TEXT                 null: false  primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
	Body string `gorm:"column:body;type:TEXT;" json:"body"`
	//[ 7] sso_id                                         UUID                 null: false  primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []
	SsoID string `gorm:"column:sso_id;type:UUID;" json:"sso_id"`
}

var commentsTableInfo = &TableInfo{
	Name: "comments",
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
			GoFieldType:        "sql.NullTime",
			JSONFieldName:      "created_at",
			ProtobufFieldName:  "created_at",
			ProtobufType:       "uint64",
			ProtobufPos:        2,
		},

		&ColumnInfo{
			Index:              2,
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
			GoFieldType:        "sql.NullTime",
			JSONFieldName:      "updated_at",
			ProtobufFieldName:  "updated_at",
			ProtobufType:       "uint64",
			ProtobufPos:        3,
		},

		&ColumnInfo{
			Index:              3,
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
			ProtobufPos:        4,
		},

		&ColumnInfo{
			Index:              4,
			Name:               "commentable_id",
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
			GoFieldName:        "CommentableID",
			GoFieldType:        "int32",
			JSONFieldName:      "commentable_id",
			ProtobufFieldName:  "commentable_id",
			ProtobufType:       "int32",
			ProtobufPos:        5,
		},

		&ColumnInfo{
			Index:              5,
			Name:               "commentable_type",
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
			GoFieldName:        "CommentableType",
			GoFieldType:        "string",
			JSONFieldName:      "commentable_type",
			ProtobufFieldName:  "commentable_type",
			ProtobufType:       "string",
			ProtobufPos:        6,
		},

		&ColumnInfo{
			Index:              6,
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
			ProtobufPos:        7,
		},

		&ColumnInfo{
			Index:              7,
			Name:               "sso_id",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "UUID",
			DatabaseTypePretty: "UUID",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "UUID",
			ColumnLength:       -1,
			GoFieldName:        "SsoID",
			GoFieldType:        "string",
			JSONFieldName:      "sso_id",
			ProtobufFieldName:  "sso_id",
			ProtobufType:       "string",
			ProtobufPos:        8,
		},
	},
}

// TableName sets the insert table name for this struct type
func (c *Comments) TableName() string {
	return "comments"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (c *Comments) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (c *Comments) Prepare() {
}

// Validate invoked before performing action, return an error if field is not populated.
func (c *Comments) Validate(action Action) error {
	return nil
}

// TableInfo return table meta data
func (c *Comments) TableInfo() *TableInfo {
	return commentsTableInfo
}
