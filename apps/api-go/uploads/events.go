package uploads

import (
	"time"

	"github.com/aws/aws-sdk-go-v2/service/s3/types"
)

type UploadedFile struct {
	Records []struct {
		S3 struct {
			Bucket struct {
				Arn           string `json:"arn"`
				Name          string `json:"name"`
				OwnerIdentity struct {
					PrincipalId string `json:"principalId"`
				} `json:"ownerIdentity"`
			} `json:"bucket"`
			Object struct {
				Key          string `json:"key"`
				ETag         string `json:"eTag"`
				Size         int    `json:"size"`
				Sequencer    string `json:"sequencer"`
				ContentType  string `json:"contentType"`
				UserMetadata struct {
					// TODO Can be variable
					ContentType string `json:"content-type"`
				} `json:"userMetadata"`
			} `json:"object"`
			ConfigurationId string `json:"configurationId"`
			S3SchemaVersion string `json:"s3SchemaVersion"`
		} `json:"s3"`
		Source struct {
			Host      string `json:"host"`
			Port      string `json:"port"`
			UserAgent string `json:"userAgent"`
		} `json:"source"`
		AwsRegion    string      `json:"awsRegion"`
		EventName    types.Event `json:"eventName"`
		EventTime    time.Time   `json:"eventTime"`
		EventSource  string      `json:"eventSource"`
		EventVersion string      `json:"eventVersion"`
		UserIdentity struct {
			PrincipalId string `json:"principalId"`
		} `json:"userIdentity"`
		ResponseElements struct {
			XAmzId2              string `json:"x-amz-id-2"`
			XAmzRequestId        string `json:"x-amz-request-id"`
			XMinioDeploymentId   string `json:"x-minio-deployment-id"`
			XMinioOriginEndpoint string `json:"x-minio-origin-endpoint"`
		} `json:"responseElements"`
		RequestParameters struct {
			Region          string `json:"region"`
			PrincipalId     string `json:"principalId"`
			SourceIPAddress string `json:"sourceIPAddress"`
		} `json:"requestParameters"`
	} `json:"Records"`
}
