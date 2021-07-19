import config from "../config/env"

export default class FetchRequest
{
  constructor(uri, method = 'GET', body = null, contentType = 'application/json')
  {
    this.url = `${config.api}${uri}`
    this.method = method
    this.body = body
    this.contentType = contentType
  }

  getBody() {
    return (this.contentType === 'application/json' && this.body !== null)
      ? JSON.stringify(this.body)
      : this.body
  }

  send() {
    const _this = this;
    return new Promise((resolve, reject) => {
      let headers = _this.contentType === 'multipart/form-data'
        ? {}
        : { 'Content-Type': _this.contentType }

      if (localStorage.user !== undefined && localStorage.user !== null) {
        const token = JSON.parse(localStorage.user).token
        headers['Authorization'] = `Bearer ${token}`
      }

      const params = {
        method: _this.method,
        mode: 'cors',
        body: _this.getBody(),
        headers: headers
      }

      return fetch(_this.url, params)
        .then(response => resolve(response.json()))
        .catch(error => reject(error));
    })
  }
}
