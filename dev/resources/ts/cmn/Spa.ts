
import axios from 'axios';

let baseURL = `${window.location.protocol}//${window.location.hostname}`;

if (window.location.port) {
  baseURL += `:${window.location.port}`;
}

const instance = axios.create({
  baseURL: baseURL,
  timeout: 10000,
  headers: {'X-Requested-With': 'XMLHttpRequest'},
});


const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (token) {
  instance.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
  console.error('CSRF token not found');
}

export default instance;