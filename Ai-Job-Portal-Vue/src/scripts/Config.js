let Production = false;

let baseURL = Production ? 'https://example.com/api/' : 'http://127.0.0.1:8000/api/';

export default {
    baseURL,
}