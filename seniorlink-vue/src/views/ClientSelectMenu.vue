<template>
  <div class="client-start">
    <header class="header">
      <div class="brand">
        <h1>SeniorLink</h1>
      </div>
  </header>
  <main class="main-content">
      <div class="profile-section">
        <div class="profile-container"> 
          <div class="profile-placeholder"></div>
        </div>
        <h2 class="welcome-message">Welcome, {{ name }}!</h2>
      </div>
    <nav>
      <ul class="nav-buttons vertical">
        <li @click="navigateTo('/profile')">View Profile</li>
        <li @click="navigateTo('./update/self')">Update Profile</li>
        <li @click="navigateTo('./qr')">View QR</li>
        <li @click="navigateTo('./transactions')">View Transactions</li>
        <li @click="navigateTo('./transactions-print')">Print Transactions</li>
      </ul>
    </nav>
    </main>
</div>
</template>

<script>
import axios from 'axios';

export default {
data() {
  return {
    name: "", // Placeholder for the name
  };
},

async mounted() {
  try {
    const response = await axios.get('/api/user'); // Replace with your API endpoint
    this.name = response.data.name; // Assuming the API response has a "name" property
  } catch (error) {
    console.error("Error fetching user data:", error);
    // Handle error, e.g., set a default name or display an error message
  }
},

methods: {
  navigateTo(route) {
    this.$router.push(route); // Navigate to the specified route
  }
}
};
</script>

<style scoped>
.client-start {
  display: flex;
  flex-direction: column;
  align-items: center;
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
  padding-left: 5%;
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
  margin-top: 4rem;
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

/* Table Styles for Responsiveness */
.table-container {
width: 100%;          /* Make table take up most of screen width */
overflow-x: auto;    /* Enable horizontal scrolling if needed */
margin: 0 auto;  /* Center the table horizontally */
}

.table {
width: 100%; 
table-layout: fixed; /* Distribute column width evenly */
border-collapse: collapse;
}

.table td {
/* Adjust padding as needed for smaller screens */
padding: 0.5rem;    
text-align: center; /* Center text in cells */
white-space: nowrap; /* Prevent text from wrapping */
border: 2px solid #acacac;
overflow: hidden; /* Hide overflowing text */
text-overflow: ellipsis;      /* Add ellipsis (...) if content overflows */
max-width: 100px;            /* Adjust max-width as needed */
}
.table th{
/* Adjust padding as needed for smaller screens */
padding: 0.5rem;    
text-align: center; /* Center text in cells */
border: 2px solid #acacac;
max-width: 100%;
overflow: hidden;
box-sizing: border-box;
min-width: fit-content; /* Shrink to fit text */
/* text-overflow: ellipsis; */
}

/* Media Query for Smaller Screens (e.g., phones) */
@media (max-width: 600px) {
.table td {
  font-size: 12px; /* Make font smaller on smaller screens */
  max-width: 100px;         /* Further reduce max-width on very small screens */
}
}
@media (max-width: 600px) {
.table th{
  font-size: 15px; /* Make font smaller on smaller screens */
  padding-top: 2%;
  padding-left: 0;
  padding-right: 0;
}
}
.profile-placeholder {
width: 150px;         
height: 150px;
background-color: #d3d3d3;  /* Placeholder background color (light gray) */
border-radius: 10%;      /* Make it a square */
cursor: pointer;
transition: background-color 0.25s; /* Smooth transition */
display: inline-flex;   /* Use inline-flex to align icon and text */
margin-right: 2rem;
margin-top: 1rem;
margin-left: 0;
}

.profile-placeholder:hover {
background-color: #808080; /* Slightly darker on hover */
}
.profile-container {
position: relative; /* Allows absolute positioning of the dropdown */
margin: 1rem 0 0 0;
align-self: auto; /* Align profile to the left within start-frame */
}

/* Responsive Profile Placeholder */
@media (min-width: 768px) { /* Adjust breakpoint as needed */
.profile-placeholder {
  width: 200px;      /* Increase size on larger screens */
  height: 200px;
  margin: 1rem; 
}
}
/* Main Content */
.main-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 2rem; /* Adjust padding as needed */
  width: 80%;
  max-width: 600px; /* This sets a maximum width to prevent the content from getting too wide on large screens */
}

/* Profile Section */
.profile-section {
  display: flex;
  align-items: center; /* Align image and welcome text vertically */
  gap: 1rem; 
}

/* Vertical Navigation Button Styles */
.nav-buttons.vertical {
  flex-direction: column; /* Stack buttons vertically */
  align-items: stretch;    /* Make buttons fill container width */
  gap: 1rem;              /* Add spacing between buttons */
  width: 90%;
}

.nav-buttons.vertical li {
  width: 100%;             /* Ensure each button takes full width */
  text-align: center;      /* Center the button text */
  margin-top: 5%;
}
.welcome-message {
  font-size: 1.2rem;
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
