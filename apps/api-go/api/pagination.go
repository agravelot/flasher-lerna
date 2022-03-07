package api

type MetaOld struct {
	Total int64 `json:"total"`
	Limit int   `json:"limit"`
}

type Meta struct {
	Total int64 `json:"total"`
	Limit int32 `json:"limit"`
}
