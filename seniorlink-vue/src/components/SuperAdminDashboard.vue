<template>
    <nav>
      <ul class="nav-buttons">
        <li @click="toggle('create')" class="dropdown" :class="{ active: activeDropdown === 'create' }">
          Create Account
          <ul v-if="activeDropdown === 'create'" class="dropdown-content">
            <li class="dropdown-buttons"><router-link to="#" @click.prevent="redirectTo('CreateTown')">Town</router-link></li>
            <li class="dropdown-buttons"><router-link to="#" @click.prevent="redirectTo('CreateEstablishment')">Establishment</router-link></li>
            <li class="dropdown-buttons"><router-link to="#" @click.prevent="redirectTo('CreateSuper')">Super Admin</router-link></li>
            </ul>
        </li>
        <li @click="toggle('view')" class="dropdown" :class="{ active: activeDropdown === 'view' }">
          View Account
          <ul v-if="activeDropdown === 'view'" class="dropdown-content">
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('ViewTown')">Towns</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('ViewBarangay')">Barangay</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('ViewClient')">Clients</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('ViewSuper')">Super Admin</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('ViewEstablishment')">Establishment</router-link></li>
            </ul>
        </li>
        <li @click="toggle('update')" class="dropdown" :class="{ active: activeDropdown === 'update' }">
          Update Account Info
          <ul v-if="activeDropdown === 'update'" class="dropdown-content">
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('UpateTown')">Towns</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('UpdateEstablishment')">Establishment</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('UpdateSuper')">Super Admin</router-link></li>
            </ul>
        </li>
        <li @click="toggle('delete')" class="dropdown" :class="{ active: activeDropdown === 'delete' }">
          Delete Account
          <ul v-if="activeDropdown === 'delete'" class="dropdown-content">
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('DeleteTown')">Towns</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('DeleteEstablishment')">Establishment</router-link></li>
            <li class="dropdown-buttons"><router-link @click.prevent="redirectTo('DeleteSuper')">Super Admin</router-link></li>
            </ul>
        </li>
        </ul>
    </nav>
</template>

<script>
import apiServices from '@/services/apiServices';

export default {
  data() {
    return {
      activeDropdown: null, // Track the currently active dropdown
      maxWidth: 0,
      showProfileDropdown: false, // New property for the profile dropdown
    };
  }
};
</script>

<style scoped>
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
