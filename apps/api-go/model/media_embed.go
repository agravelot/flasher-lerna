package model

import (
	"bytes"
	"database/sql/driver"
	"encoding/json"
	"errors"
	"fmt"
)

type CustomProperties struct {
	Height int32 `json:"height"`
	Width  int32 `json:"width"`
}

func (cp CustomProperties) Value() (driver.Value, error) {
	return json.Marshal(cp)
}

func (cp *CustomProperties) Scan(value interface{}) error {
	bytes, ok := value.([]byte)
	if !ok {
		return errors.New(fmt.Sprint("failed to unmarshal JSONB value:", value))
	}

	result := CustomProperties{}
	err := json.Unmarshal(bytes, &result)
	*cp = result
	return err
}

type ResponsiveImages struct {
	Responsive Responsive `gorm:"" json:"responsive"`
}

func (ri ResponsiveImages) Value() (driver.Value, error) {
	return json.Marshal(ri)
}

func (ri *ResponsiveImages) Scan(value interface{}) error {
	b, ok := value.([]byte)
	// Defaults value is []
	if bytes.Equal(b, []byte("[]")) {
		return nil
	}
	if !ok {
		return errors.New(fmt.Sprint("failed to unmarshal JSONB value:", value))
	}

	result := ResponsiveImages{}
	err := json.Unmarshal(b, &result)
	*ri = result
	return err
}

type Responsive struct {
	Urls      []string `gorm:"type:text[];" json:"urls"`
	Base64Svg string   `json:"base64svg"`
}

type GeneratedConversions struct {
	Thumb      bool `json:"thumb"`
	Responsive bool `json:"responsive"`
}
