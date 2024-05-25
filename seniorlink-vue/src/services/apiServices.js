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
    return response.data;
  },
  post: async (url, data) => {
    const response = await api.post(url, data);
    return response.data;
  },
  put: async (url, data) => {
    const response = await api.put(url, data);
    return response.data;
  },
  delete: async (url) => {
    const response = await api.delete(url);
    return response.data;
  }
};

export default apiServices;
