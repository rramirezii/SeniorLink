import axios from 'axios';

// Create an Axios instance
const apiClient = axios.create({
  baseURL: 'http://your-lumen-backend-url/api', // Replace with your Lumen backend URL
  headers: {
    'Content-Type': 'application/json',
  }
});

// Define your API calls
export default {
  getData() {
    return apiClient.get('/data');
  },
  // Add more API calls as needed
};
