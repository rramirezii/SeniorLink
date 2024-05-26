<template>
  <div class="senior-link">
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
    <nav>
      <ul class="nav-buttons">
        <li @click="toggle('create')" class="dropdown" :class="{ active: activeDropdown === 'create' }">
          Create Account
          <ul v-if="activeDropdown === 'create'" class="dropdown-content">
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('CreateTown')">Town</router-link></li>
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('CreateEstablishment')">Establishment</router-link></li>
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('CreateSuperAdmin')">Super Admin</router-link></li>
            </ul>
        </li>
        <li @click="toggle('view')" class="dropdown" :class="{ active: activeDropdown === 'view' }">
          View Account
          <ul v-if="activeDropdown === 'view'" class="dropdown-content">
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('ViewTown')">Towns</router-link></li>
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('ViewBarangay')">Barangay</router-link></li>
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('ViewClientList')">Clients</router-link></li>
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('ViewSuperAdminUpdateDelete')">Super Admin</router-link></li>
            <li class="dropdown-buttons"><router-link to='#' @click.prevent="redirectTo('ViewEstablishmentUpdateDelete')">Establishment</router-link></li>
            </ul>
        </li>
        </ul>
    </nav>
  </div>
</template>

<script>

export default {
  data() {
    return {
      activeDropdown: null, // Track the currently active dropdown
      maxWidth: 0,
      showProfileDropdown: false, // New property for the profile dropdown
    };
  },
  methods: {
    toggle(dropdown) {
      // Close other dropdowns if a different one is clicked
      if (this.activeDropdown && this.activeDropdown !== dropdown) {
        this.activeDropdown = null;
      } 

      // Toggle the clicked dropdown
      this.activeDropdown = this.activeDropdown === dropdown ? null : dropdown;

      // Calculate max width when dropdown is opened
      if (this.active) {
        this.$nextTick(() => {
          const links = this.$el.querySelectorAll('.dropdown-content a');
          this.maxWidth = Math.max(...[...links].map(link => link.offsetWidth));
        });
      }
    }
  }
};
</script>

<style scoped>
.senior-link {
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
  text-decoration: none;
}

.nav-buttons li a { /* Style for links within nav-buttons */
  color: #ffffff; /* Default white color for other links */
}

.nav-buttons a {
    color: #ffffff;
    text-decoration: none; /* Remove underline for all buttons */
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
  padding-top: 2%;
  padding-bottom: 1%;
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

/* Profile Dropdown */
.dropdown-profile {
  position: absolute;
  top: 100%;         /* Position below the profile placeholder */
  right: 0;          /* Align to the right edge of the placeholder */
  width: 50px;      /* Adjust the width as needed */
  /* Prevent Overflow */
  transform: translateX(calc(-50% + 30px)); /* Adjust as needed */ 
  border: #000 1px solid;
  text-align: center; /* Center text within the dropdown */
}
.dropdown-profile ul {
  padding: 0;
  margin: 0;
  display: inline;
}

.dropdown-profile-item {  
  list-style: none;
  padding: 0.5rem;      
  width: 100%;       
  text-align: center; 
}
.dropdown-profile-item a{
  text-align: center;
  text-decoration: none;
  color: #2c3e50;
  text-align: center; /* Center text within the dropdown */
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
