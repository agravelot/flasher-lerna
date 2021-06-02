import { CustomError } from 'ts-custom-error'

export class HttpRequestError extends CustomError {
  public response: Response;

  constructor(response: Response, message: string) {
    super(message);
    this.response = response;
  }
}

export class UnprocessableEntity extends HttpRequestError {}

export class HttpNotFound extends HttpRequestError {}

class ApiResponse<T> {
  public response: Response;

  constructor(response: Response) {
    this.response = response;
  }

  json = (): Promise<T> => this.response.json();
}

const baseUrl = process.env.NEXT_PUBLIC_API_URL ?? '';

export async function api<T>(
  url: string,
  init?: RequestInit
): Promise<ApiResponse<T>> {
  init = {
    ...init,
    headers: {
      ...init?.headers,
      Accept: 'application/json',
    },
  };

  return fetch(`${baseUrl}${url}`, init).then((response) => {
    if (response.status === 404) {
      throw new HttpNotFound(response, response.statusText);
    }

    if (response.status === 422) {
      throw new UnprocessableEntity(response, response.statusText);
    }

    if (!response.ok) {
      throw new HttpRequestError(response, response.statusText);
    }

    return new ApiResponse<T>(response);
  });
}

export interface WrappedResponse<T> {
  data: T;
}

export interface PaginatedReponse<T> extends WrappedResponse<T> {
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
  };
}
