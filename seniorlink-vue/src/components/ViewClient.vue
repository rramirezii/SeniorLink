<template>
  <div class="view-client">
    <header class="header" @click.stop>
      <div class="brand">
        <h1>SeniorLink</h1>
      </div>
      <div class="search-bar">
        <!-- <input type="text" placeholder="Search..." />
        <button>Search</button> -->
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
    </header>
    <div class="main-content">
      <h2>Client Details</h2>
    <div class="client-info">
      <label for="clientName">Client Name:</label>
      <span id="clientName">{{ client.name }}</span>
    </div>
    <table class="center-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Commodities/Medicine</th>
          <th>Qty.</th>
          <th>Amount of Purchase</th>
          <th>Balance</th>
          <th>Establishment Name</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in client.transactions" :key="transaction.id">
          <td>{{ transaction.date }}</td>
          <td>{{ transaction.commodities }}</td>
          <td>{{ transaction.qty }}</td>
          <td>{{ transaction.formatCurrency(amount) }}</td>
          <td>{{ transaction.balance }}</td>
          <td>{{ transaction.establishmentName }}</td>
        </tr>
      </tbody>
    </table>
    <button @click="goBack" class="back-button">
      <i class="fas fa-arrow-left"></i> Back to Home
    </button>
  </div>
  </div>
</template>

<script>
export default {
  props: {
    client: Object, // Client object with details and transactions array
  },
  methods: {
    formatCurrency(value) {
      return `Php ${value.toFixed(2)}`; 
    },
    goBack() {
      this.$router.go(-1); // Use the path directly
    }
  },
};
</script>

<style scoped>
.view-client {
  width: 100%; /* Optional: Set a width */
  margin: 1rem auto; /* Optional: Add some margin for spacing */
}

.client-info {
  margin-bottom: 1rem;
}

.client-info label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

table {
  width: 100%; /* Make the table fill the available space */
  border-collapse: collapse; /* Remove gaps between table cells */
}

th,
td {
  padding: 0.5rem 1rem; /* Add padding for readability */
  border: 1px solid #ddd; /* Add a thin border */
  text-align: left; /* Align text to the left */
}

th {
  background-color: #f0f0f0; /* Optional: Highlight table headers */
  font-weight: bold; /* Make headers bold */
}

.center-table {
  margin-left: auto;
  margin-right: auto;
  /*width: fit-content; /* make table only as wide as its content */
}

.main-content {
  padding-top: 10px; /* Adjust the value to match your header height */
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
  padding: 0rem 1rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional subtle shadow */
  z-index: 10; /* Ensure the header stays on top of other elements */
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
}

.dropdown-content ul {
  display: flex;   
  flex-direction: column; 
  align-items: center;  /* Center items horizontally */
  border: 1px black solid;
  padding: 0%;
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
.back-button {
  background-color: #2c3e50;
  color: white;
  padding: 15px 50px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  width: auto;
  font-weight: bold;
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
