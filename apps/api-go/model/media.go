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


Table: media
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] model_type                                     VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] model_id                                       INT8                 null: false  primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 3] collection_name                                VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 4] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 5] file_name                                      VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 6] mime_type                                      VARCHAR(255)         null: true   primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 7] disk                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 8] size                                           INT8                 null: false  primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 9] manipulations                                  JSON                 null: false  primary: false  isArray: false  auto: false  col: JSON            len: -1      default: []
[10] custom_properties                              JSON                 null: false  primary: false  isArray: false  auto: false  col: JSON            len: -1      default: []
[11] responsive_images                              JSON                 null: false  primary: false  isArray: false  auto: false  col: JSON            len: -1      default: []
[12] order_column                                   INT4                 null: true   primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []
[13] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[14] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[15] uuid                                           UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []
[16] conversions_disk                               VARCHAR(255)         null: true   primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []


JSON Sample
-------------------------------------
{    "id": 31,    "model_type": "AfwJtvoGRgvjytXEnobZUhqZZ",    "model_id": 96,    "collection_name": "QHXymmlGFQCujYDxHuscTNGeo",    "name": "gPqwZpEsvyIEKvFJkrkpAmToh",    "file_name": "ikXPDjXhJfIDwpDMbncxwPWVt",    "mime_type": "tGwfEKnXdYfGuqRYSgLvdPqkI",    "disk": "vAmifWLJadmRwmRAyQiDBkitb",    "size": 82,    "manipulations": "ZoNtvsImKdurYPWHWgoMidDgs",    "custom_properties": "iNhxImDDBrNnkjOqhEQDqoFKY",    "responsive_images": "eDKCQpdfUUaEkSxdAoRZKDiJR",    "order_column": 47,    "created_at": "2179-02-28T00:14:23.296677158+01:00",    "updated_at": "2077-12-19T11:17:38.221904598+01:00",    "uuid": "eeHKfcXQUVtlBokhOgPtuQNEw",    "conversions_disk": "VWBCHOdwXfmVUTekIXaAXHXqF"}



*/

// Media struct is a row record of the media table in the flasher database
type Media struct {
	//[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
	ID int32 `gorm:"primary_key;column:id;type:INT4;" json:"id"`
	//[ 1] model_type                                     VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	ModelType string `gorm:"column:model_type;type:VARCHAR;size:255;" json:"model_type"`
	//[ 2] model_id                                       INT8                 null: false  primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
	ModelID int64 `gorm:"column:model_id;type:INT8;" json:"model_id"`
	//[ 3] collection_name                                VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	CollectionName string `gorm:"column:collection_name;type:VARCHAR;size:255;" json:"collection_name"`
	//[ 4] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Name string `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	//[ 5] file_name                                      VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	FileName string `gorm:"column:file_name;type:VARCHAR;size:255;" json:"file_name"`
	//[ 6] mime_type                                      VARCHAR(255)         null: true   primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	MimeType null.String `gorm:"column:mime_type;type:VARCHAR;size:255;" json:"mime_type"`
	//[ 7] disk                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	Disk string `gorm:"column:disk;type:VARCHAR;size:255;" json:"disk"`
	//[ 8] size                                           INT8                 null: false  primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
	Size int64 `gorm:"column:size;type:INT8;" json:"size"`
	//[ 9] manipulations                                  JSON                 null: false  primary: false  isArray: false  auto: false  col: JSON            len: -1      default: []
	Manipulations string `gorm:"column:manipulations;type:JSON;" json:"manipulations"`
	//[10] custom_properties                              JSON                 null: false  primary: false  isArray: false  auto: false  col: JSON            len: -1      default: []
	CustomProperties string `gorm:"column:custom_properties;type:JSON;" json:"custom_properties"`
	//[11] responsive_images                              JSON                 null: false  primary: false  isArray: false  auto: false  col: JSON            len: -1      default: []
	ResponsiveImages string `gorm:"column:responsive_images;type:JSON;" json:"responsive_images"`
	//[12] order_column                                   INT4                 null: true   primary: false  isArray: false  auto: false  col: INT4            len: -1      default: []
	OrderColumn null.Int `gorm:"column:order_column;type:INT4;" json:"order_column"`
	//[13] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	CreatedAt null.Time `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	//[14] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
	UpdatedAt null.Time `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	//[15] uuid                                           UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []
	UUID null.String `gorm:"column:uuid;type:UUID;" json:"uuid"`
	//[16] conversions_disk                               VARCHAR(255)         null: true   primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
	ConversionsDisk null.String `gorm:"column:conversions_disk;type:VARCHAR;size:255;" json:"conversions_disk"`
}

var mediaTableInfo = &TableInfo{
	Name: "media",
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
			Name:               "model_type",
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
			GoFieldName:        "ModelType",
			GoFieldType:        "string",
			JSONFieldName:      "model_type",
			ProtobufFieldName:  "model_type",
			ProtobufType:       "string",
			ProtobufPos:        2,
		},

		&ColumnInfo{
			Index:              2,
			Name:               "model_id",
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
			GoFieldName:        "ModelID",
			GoFieldType:        "int64",
			JSONFieldName:      "model_id",
			ProtobufFieldName:  "model_id",
			ProtobufType:       "int32",
			ProtobufPos:        3,
		},

		&ColumnInfo{
			Index:              3,
			Name:               "collection_name",
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
			GoFieldName:        "CollectionName",
			GoFieldType:        "string",
			JSONFieldName:      "collection_name",
			ProtobufFieldName:  "collection_name",
			ProtobufType:       "string",
			ProtobufPos:        4,
		},

		&ColumnInfo{
			Index:              4,
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
			ProtobufPos:        5,
		},

		&ColumnInfo{
			Index:              5,
			Name:               "file_name",
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
			GoFieldName:        "FileName",
			GoFieldType:        "string",
			JSONFieldName:      "file_name",
			ProtobufFieldName:  "file_name",
			ProtobufType:       "string",
			ProtobufPos:        6,
		},

		&ColumnInfo{
			Index:              6,
			Name:               "mime_type",
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
			GoFieldName:        "MimeType",
			GoFieldType:        "null.String",
			JSONFieldName:      "mime_type",
			ProtobufFieldName:  "mime_type",
			ProtobufType:       "string",
			ProtobufPos:        7,
		},

		&ColumnInfo{
			Index:              7,
			Name:               "disk",
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
			GoFieldName:        "Disk",
			GoFieldType:        "string",
			JSONFieldName:      "disk",
			ProtobufFieldName:  "disk",
			ProtobufType:       "string",
			ProtobufPos:        8,
		},

		&ColumnInfo{
			Index:              8,
			Name:               "size",
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
			GoFieldName:        "Size",
			GoFieldType:        "int64",
			JSONFieldName:      "size",
			ProtobufFieldName:  "size",
			ProtobufType:       "int32",
			ProtobufPos:        9,
		},

		&ColumnInfo{
			Index:              9,
			Name:               "manipulations",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "JSON",
			DatabaseTypePretty: "JSON",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "JSON",
			ColumnLength:       -1,
			GoFieldName:        "Manipulations",
			GoFieldType:        "string",
			JSONFieldName:      "manipulations",
			ProtobufFieldName:  "manipulations",
			ProtobufType:       "string",
			ProtobufPos:        10,
		},

		&ColumnInfo{
			Index:              10,
			Name:               "custom_properties",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "JSON",
			DatabaseTypePretty: "JSON",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "JSON",
			ColumnLength:       -1,
			GoFieldName:        "CustomProperties",
			GoFieldType:        "string",
			JSONFieldName:      "custom_properties",
			ProtobufFieldName:  "custom_properties",
			ProtobufType:       "string",
			ProtobufPos:        11,
		},

		&ColumnInfo{
			Index:              11,
			Name:               "responsive_images",
			Comment:            ``,
			Notes:              ``,
			Nullable:           false,
			DatabaseTypeName:   "JSON",
			DatabaseTypePretty: "JSON",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "JSON",
			ColumnLength:       -1,
			GoFieldName:        "ResponsiveImages",
			GoFieldType:        "string",
			JSONFieldName:      "responsive_images",
			ProtobufFieldName:  "responsive_images",
			ProtobufType:       "string",
			ProtobufPos:        12,
		},

		&ColumnInfo{
			Index:              12,
			Name:               "order_column",
			Comment:            ``,
			Notes:              ``,
			Nullable:           true,
			DatabaseTypeName:   "INT4",
			DatabaseTypePretty: "INT4",
			IsPrimaryKey:       false,
			IsAutoIncrement:    false,
			IsArray:            false,
			ColumnType:         "INT4",
			ColumnLength:       -1,
			GoFieldName:        "OrderColumn",
			GoFieldType:        "null.Int",
			JSONFieldName:      "order_column",
			ProtobufFieldName:  "order_column",
			ProtobufType:       "int32",
			ProtobufPos:        13,
		},

		&ColumnInfo{
			Index:              13,
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
			ProtobufPos:        14,
		},

		&ColumnInfo{
			Index:              14,
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
			ProtobufPos:        15,
		},

		&ColumnInfo{
			Index:              15,
			Name:               "uuid",
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
			GoFieldName:        "UUID",
			GoFieldType:        "null.String",
			JSONFieldName:      "uuid",
			ProtobufFieldName:  "uuid",
			ProtobufType:       "string",
			ProtobufPos:        16,
		},

		&ColumnInfo{
			Index:              16,
			Name:               "conversions_disk",
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
			GoFieldName:        "ConversionsDisk",
			GoFieldType:        "null.String",
			JSONFieldName:      "conversions_disk",
			ProtobufFieldName:  "conversions_disk",
			ProtobufType:       "string",
			ProtobufPos:        17,
		},
	},
}

// TableName sets the insert table name for this struct type
func (m *Media) TableName() string {
	return "media"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (m *Media) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (m *Media) Prepare() {
}

// Validate invoked before performing action, return an error if field is not populated.
func (m *Media) Validate(action Action) error {
	return nil
}

// TableInfo return table meta data
func (m *Media) TableInfo() *TableInfo {
	return mediaTableInfo
}
