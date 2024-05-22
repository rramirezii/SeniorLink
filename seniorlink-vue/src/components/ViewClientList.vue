<template>
    <div>
    <h2>Seniors List</h2>
    </div>
    <div class="table-container">
      <p v-if="loading" class="loading-message">Loading...</p>
      <table v-else class="table">
        <thead>
          <tr>
            <th v-for="header in tableHeaders" :key="header">
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredTableData.length === 0">
            <td colspan="9" class="no-results">No results found.</td>
          </tr>
          <tr v-for="item in filteredTableData" :key="item.id"> 
            <td v-for="header in tableHeaders" :key="header">
              {{ item[header] }} 
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>
  
  <script>
import apiServices from '@/services/apiServices';

  export default {
    data() {
      return {
        tableHeaders: ['First Name', 'Middle Name', 'Last Name', 'OSCA ID', 'Barangay', 'Birthday', 'Contact Number', 'QR'],  // Default headers
        tableData: [],
        searchQuery: '',
        loading: true,
        excludedFields: ['id'], // Array of fields to exclude
      };
    },
    computed: {
    filteredTableData() {
        const query = this.searchQuery.toLowerCase();
        return this.tableData.filter(item => {
        return this.tableHeaders.some(header => {
            if (header.toLowerCase() !== 'id' && header !== 'Birthday' && header !== 'QR' && header !== 'Password') { // Exclude the "id" column
            return String(item[header]).toLowerCase().includes(query);
            } else {
            return false; // Don't include "id" in the search
            }
        });
        });
    },
    },
    async mounted() {
      try {
        const response = await apiServices.get('/senior.json');  //file should be in the `public` folder 
        this.tableData = response.data;
       
        this.loading = false;
      } catch (error) {
        console.error("Error fetching data:", error);
        this.loading = false;
        // Handle errors appropriately (show an error message to the user)
      } 
    },
    methods: {
      performSearch() {
        console.log("Searching for:", this.searchQuery);
      }
    }
  };
  </script>
  
  <style scoped>
 
  .table-container {
  margin-top: 60px; /* Adjust as needed */
  width: 80%; /* Or set a specific width */
  margin: 0 auto;  /* Center the table horizontally */
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th, .table td {
  border: 1px solid #ddd;
  padding: 8px;
}
  </style>
  
    <style>
    #app {
      font-family: Avenir, Helvetica, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-align: center;
      color: #2c3e50;
      margin-top: 60px;
    }
    </style>
  