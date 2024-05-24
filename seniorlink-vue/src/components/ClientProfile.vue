<template>
    <div class="client-profile">
      <header class="header">
        <div class="brand">
          <h1>SeniorLink</h1>
        </div>
        <div class="profile-and-search">
        <!-- <div class="search-bar">
          <input type="text" placeholder="Search..." v-model="searchQuery" />
          <button @click="performSearch">Search</button>
        </div> -->
        <div class="profile-container" @click="toggleProfileDropdown"> 
        <router-link to="/profile">
          <div class="profile-placeholder-mini"></div>
        </router-link>
        <!-- <ul v-if="showProfileDropdown" class="dropdown-profile">
          <li class="dropdown-buttons">
            <a href="#" @click.prevent="signOut">Sign Out</a>
          </li>
        </ul> -->
      </div>
        </div> 
    </header>
    <h2 class="heading">My Profile</h2>
    <main class="main-content">
      <div v-if="loading" class="loading-message">Loading profile...</div>
      <div v-else-if="error" class="error-message">{{ error }}</div>
      <div v-else class="profile-details">
        <div class="profile-image">
          <img v-if="profileImage" :src="profileImage" alt="Profile" />
          <img v-else src="/image.jpg" alt="Profile placeholder" class="dp-holder" /> 
        </div>
        <div class="profile-details">
          <div class="detail-row">
            <span class="label">Name:</span>
            <span class="value">{{ profileData['First Name'] }} {{ profileData['Middle Name'] }} {{ profileData['Last Name'] }}</span>
          </div>
          <div class="detail-row">
            <span class="label">OSCA ID:</span>
            <span class="value">{{ profileData['OSCA ID'] }}</span>
          </div>
          <div class="detail-row">
            <span class="label">Barangay:</span>
            <span class="value">{{ profileData.Barangay }}</span>
          </div>
          <div class="detail-row">
            <span class="label">Birthday:</span>
            <span class="value">{{ formatDate(profileData.Birthday) }}</span>
          </div>
          <div class="detail-row">
            <span class="label">Contact Number:</span>
            <span class="value">{{ profileData['Contact Number'] }}</span>
          </div>
          <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required>
      </div>
        </div>
      </div>
      <button @click="goBack" class="back-button">Back to Home</button>
    </main>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      profileData: null,
      profileImage: null,
      loading: true,
      error: null,
    };
  },
  created() {
    this.fetchProfileData();
  },
  methods: {
    async fetchProfileData() {
      try {
        const response = await axios.get('/profile.json');
        this.profileData = response.data;
        this.profileImage = response.data.Image; 
      } catch (error) {
        console.error('Error fetching profile data:', error);
        this.error = 'Failed to load profile';
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      const date = new Date(dateString);
      return date.toLocaleDateString(undefined, options);
    },
  },
};
</script>
  
  <style scoped>
  .client-profile {
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
.profile-placeholder-mini {
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
.profile-placeholder {
  width: 150px;
  height: 150px;
  background-color: #d3d3d3;
  border-radius: 50%;  /* Make it a circle */
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 50px; /* Adjust for the size of the icon */
}

.profile-details {
  margin-top: 50px;
  text-align: left;
}

.detail-row {
  display: flex;
  margin-bottom: 10px;
}

.label {
  font-weight: bold;
  margin-right: 10px;
}

.heading{
  margin-top: 4rem;
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
  margin-top: 7rem;
}
.profile-image { /* Added to center the profile image */
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}

.profile-image img, 
.profile-image i {
  width: 150px;
  height: 150px;
}

/* Adjusted detail-row flex alignment */
.detail-row {
  display: flex;
  justify-content: flex-start;  /* Align items to the left */
  align-items: center;
  margin-bottom: 10px;
  margin-top: 20px;
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
  