<template>
    <div class="view-select-barangay">
      <header class="header">
        <div class="brand">
          <h1>SeniorLink</h1>
        </div>
        <div class="profile-and-search">
        <div class="search-bar">
          <input type="text" placeholder="Search..." v-model="searchQuery" />
          <button @click="performSearch">Search</button>
        </div>
        <div class="profile-container" @click="toggleProfileDropdown"> 
        <router-link to="/profile">
          <div class="profile-placeholder"></div>
        </router-link>
        <!-- <ul v-if="showProfileDropdown" class="dropdown-profile">
          <li class="dropdown-buttons">
            <a href="#" @click.prevent="signOut">Sign Out</a>
          </li>
        </ul> -->
      </div>
        </div> 
    </header>
    <div>
    <h2>Towns List</h2>
  </div>
  <div class="table-container">
      <p v-if="loading" class="loading-message">Loading...</p>
      <table v-else class="table">
        <thead>
          <tr>
            <th v-for="header in tableHeaders" :key="header">
              {{ header }}
            </th>
            <th>Actions</th> 
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredTableData.length === 0">
            <td colspan="3" class="no-results">No results found.</td> 
          </tr>
          <tr v-for="item in filteredTableData" :key="item.id"> 
            <td v-for="header in tableHeaders" :key="header">
              {{ item[header] }}
            </td>
            <td>
              <router-link :to="{ name: 'ViewBarangay', params: { id: item.id }}">
                <button class="view-button">View</button>
              </router-link>
              <button @click="deleteItem(item.id)" class="delete-button">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        tableHeaders: ['Name', 'Zip Code'],  // Default headers
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
            if (header.toLowerCase() !== 'id') { // Exclude the "id" column
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
        const response = await axios.get('/data.json');  //file should be in the `public` folder 
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
    },
    navigateToTown(id) {
      console.log("Navigating to town with ID:", id);
      this.$router.push({ name: 'ViewTown', params: { id: id } });
    }
  };
  </script>

<style scoped>
.view-select-barangay {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem;
}

.header {
  position: fixed; /* Stick to the top */
  top: 0; /* Position at the top */
  left: 0; /* Align to the left */
  width: 100%; /* Full width */
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff; /* Optional background color for the header */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional subtle shadow */
  z-index: 10; /* Ensure the header stays on top of other elements */
}

.profile-and-search {
display: flex; /* Makes this a flex container */
align-items: center; /* Vertically aligns items within this container */
gap: 1rem; /* Add some space between the search and profile elements */
}

.brand{
  padding-left: 2%;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
}

/* search bar */
.search-bar {
  display: flex;
  align-items: center;
}

.search-bar input {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-right: 1rem;
}

.search-bar button {
  padding: 0.5rem 1rem;
  background-color: #2c3e50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 0cm;
}

/* buttons */
nav {
  width: 100%;
  margin-top: 200px;
}

nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  /* justify-content: space-around; */
  justify-content: center;
}

nav li {
  margin-right: 1rem;
  background-color: #2c3e50; /* Light gray background - Adjust as desired */
  padding: 1rem; /* Some padding for visual clarity */
  border-radius: 6px; /* Slightly round the corners */
  color: white;
  font-weight: bold;
  flex: 0 0 auto; 
}
nav li:hover{
  background-color: #ccc;
  transition: background-color 0.25s;
  color: rgb(75, 69, 69);
}

.nav-buttons {
  display: flex;
  justify-content: center; /* Center buttons horizontally */ 
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-buttons li {
  margin-right: 1rem; /* Adjust margin between buttons as needed */
  position: relative; /* Crucial for containing the dropdown */
}

a {
  text-decoration: none;
  color: #000;
}

a:hover {
  color: #2c3e50;
}

/* profile logo */
.profile-link {
  display: flex;
  align-items: center;
  padding: 0.5rem;
  margin-right: 1rem;
  border-radius: 4px;
  color: #000; /* Color of the icon and text */
}

.profile-link:hover {
  background-color: #eee; /* Optional hover background color */
}

.dropdown-content {
  position: absolute;
  top: 100%;
  left: 0;
  background-color: #ccc; /* Gray background */
  border-radius: 6px;      /* Match the parent button's rounded corners */
  width: 85%; 
  padding: 0.5rem;         /* Add padding for spacing */
  display: flex;          /* Enable flexbox for vertical alignment */
  flex-direction: column; /* make linear top to bottom */
  margin-top: 5%;
  /* padding-left: 10%; */
  height: fit-content;
}

.dropdown-content ul {
  display: flex;   
  flex-direction: column; 
  align-items: center;  /* Center items horizontally */
  border: 1px black solid;
  padding: 0%;
  height: fit-content;
}


.dropdown-content a {   
  color: #2c3e50;         /* Link color */
  text-decoration: none; 
  margin-bottom: 0.5rem; /* Add margin between links */
  display: block;         /* Make links take up full width */
  width: auto;  /* Allow dropdown to naturally adjust width */
  align-items: stretch; /* Stretch items to fill the container's width */
  display: inline-block; /* Allow text to wrap naturally */ 
  padding: 0.5rem;        /* Add padding for better spacing around text */
}

.dropdown-buttons{
  color: #fff;              /* White text color */
  text-decoration: none;
  font-size: 0.8rem;       /* Smaller font size */
  margin-bottom: 0.25rem;  /* Smaller margin between links */
  /* display: block; */
  width: 100%;       /* Make each button take full width */
  box-sizing: border-box; /* Include padding and border in the width calculation */
  display: flex;            /* Enable flexbox for centering */
  justify-content: center; /* Center the text horizontally */
  align-items: center;    /* Center the text vertically */
}

.dropdown-buttons a {
  display: block;     /* Make sure links fill the width */
  white-space: nowrap; /* Prevent text from wrapping */
}

.profile-button {
  display: inline-flex;   /* Use inline-flex to align icon and text */
  align-items: center;
  padding: 0.5rem 1rem; 
  margin-right: 1rem;
  background-color: #2c3e50;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  text-decoration: none; /* Remove default underline from link */
}

.profile-button:hover {
  background-color: #ccc;
  transition: background-color 0.25s;
  color: rgb(75, 69, 69);
}

.profile-button i {
  margin-right: 0.5rem; /* Add some space between the icon and text */
}

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

.view-button{
  padding: 0.5rem 1rem;
  background-color: #2c3e50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 0cm;
}
.profile-placeholder {
  width: 55px;         
  height: 55px;
  background-color: #d3d3d3;  /* Placeholder background color (light gray) */
  border-radius: 10%;      /* Make it a square */
  cursor: pointer;
  transition: background-color 0.25s; /* Smooth transition */
  display: inline-flex;   /* Use inline-flex to align icon and text */
  margin-right: 2rem;
  margin-top: 1ex;
}

.profile-placeholder:hover {
  background-color: #808080; /* Slightly darker on hover */
}
.profile-container {
  position: relative; /* Allows absolute positioning of the dropdown */
}
.delete-button{
  padding: 0.5rem 1rem;
  background-color: #7e3e3e;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 0cm;
  margin-left: 10px;
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
