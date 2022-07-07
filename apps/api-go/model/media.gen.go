// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

import (
	"time"
)

const TableNameMedium = "media"

// Medium mapped from table <media>
type Medium struct {
	ID               int32             `gorm:"column:id;type:int4;primaryKey;autoIncrement:true" json:"id"`
	ModelType        string            `gorm:"column:model_type;type:varchar;not null;index:media_model_type_model_id_index,priority:1" json:"model_type"`
	ModelID          int64             `gorm:"column:model_id;type:int8;not null;index:media_model_type_model_id_index,priority:2" json:"model_id"`
	CollectionName   string            `gorm:"column:collection_name;type:varchar;not null" json:"collection_name"`
	Name             string            `gorm:"column:name;type:varchar;not null" json:"name"`
	FileName         string            `gorm:"column:file_name;type:varchar;not null" json:"file_name"`
	MimeType         *string           `gorm:"column:mime_type;type:varchar" json:"mime_type"`
	Disk             string            `gorm:"column:disk;type:varchar;not null" json:"disk"`
	Size             int64             `gorm:"column:size;type:int8;not null" json:"size"`
	Manipulations    string            `gorm:"column:manipulations;type:json;not null" json:"manipulations"`
	CustomProperties *CustomProperties `gorm:"column:custom_properties;type:json;not null" json:"custom_properties"`
	ResponsiveImages *ResponsiveImages `gorm:"column:responsive_images;type:json;not null" json:"responsive_images"`
	OrderColumn      *int32            `gorm:"column:order_column;type:int4" json:"order_column"`
	CreatedAt        *time.Time        `gorm:"column:created_at;type:timestamp" json:"created_at"`
	UpdatedAt        *time.Time        `gorm:"column:updated_at;type:timestamp" json:"updated_at"`
	UUID             *string           `gorm:"column:uuid;type:uuid" json:"uuid"`
	ConversionsDisk  *string           `gorm:"column:conversions_disk;type:varchar" json:"conversions_disk"`
}

// TableName Medium's table name
func (*Medium) TableName() string {
	return TableNameMedium
}
