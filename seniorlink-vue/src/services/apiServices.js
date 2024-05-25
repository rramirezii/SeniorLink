import axios from 'axios';

const api = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL || '/api', // Default to '/api' if not defined
  headers: {
    'Content-Type': 'application/json'
  }
});

const apiServices = {
  get: async (url) => {
    const response = await api.get(url);
    return response;
  },
  post: async (url, data) => {
    const response = await api.post(url, data);
    return response;
  },
  put: async (url, data) => {
    const response = await api.put(url, data);
    return response;
  },
  delete: async (url) => {
    const response = await api.delete(url);
    return response;
  }
};

export default apiServices;
