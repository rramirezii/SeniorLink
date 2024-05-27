<template>
  <div class="senior-link">
    <header class="header">
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
    <main>
      <div v-show="seniorProfile">
        <main class="main-content">
          <div v-if="loading" class="loading-message">Loading profile...</div>
          <div v-else-if="error" class="error-message">{{ error }}</div>
          <div v-else class="profile-details">
            <div class="profile-image">
              <img v-if="profileImage" :src="profileImage" alt="Profile" />
              <img v-else :src="require('@/assets/images/profile-default.png')" alt="Profile placeholder" class="dp-holder" /> 
            </div>
            <div class="profile-details" v-if="profileData">
              <div class="detail-row">
                <span class="label">Name: </span>
                <span class="value">{{ profileData['fname'] }} {{ profileData['mname'] }} {{ profileData['lname'] }}</span>
              </div>
              <div class="detail-row">
                <span class="label">OSCA ID: </span>
                <span class="value">{{ profileData['osca_id'] }}</span>
              </div>
              <div class="detail-row">
                <span class="label">Address: </span>
                <span class="value">{{ address }}</span>
              </div>
              <div class="detail-row">
                <span class="label">Birthday: </span>
                <span class="value">{{ formatDate(profileData.birthdate) }}</span>
              </div>
              <div class="detail-row">
                <span class="label">Contact Number: </span>
                <span class="value">{{ profileData['contact_number'] }}</span>
              </div>
            </div>
          </div>
          <button @click="proceed" class="proceed-button">Proceed</button>
        </main>
      </div>
      <div v-show="showInit" class="senior-input">
        <input type="text" placeholder="Senior ID" id="senior-username-text" v-model="seniorId" />
        <div class="button-container">
          <button @click="openTransaction">Open Transaction</button>
          <button @click="scanSeniorQR">Scan Senior QR</button>
        </div>
        <div v-if="showScanner" class="scanner">
          <video ref="video" autoplay></video>
        </div>
      </div>
      <nav v-show="showNav">
        <ul class="nav-buttons">
          <li><router-link to='#' @click.prevent="redirectToLocal('CreateTransaction', {senior_username: this.senior_username})">Create Transaction</router-link></li>
          <li><router-link to='#' @click.prevent="redirectToLocal('ViewTransaction', {senior_username: this.senior_username})">View Transactions</router-link></li>
          </ul>
      </nav>
    </main>
  </div>
</template>

<script>
import jsQR from 'jsqr';
import apiServices from '@/services/apiServices';
import router from '@/router';

export default {
  data() {
    return {
      activeDropdown: null, // Track the currently active dropdown
      maxWidth: 0,
      seniorId: '',
      showNav: false,
      showScanner: true,
      showInit: true,
      senior_username: '',
      seniorProfile: false,
      profileData: null,
      profileImage: null,
      address: '',
      error: '',
      loading: false,
    };
  },
  methods: {
    toggle(dropdown) {
      // Close other dropdowns if a different one is clicked
      if (this.activeDropdown && this.activeDropdown !== dropdown) {
        this.activeDropdown = null;
      } 
      this.activeDropdown = this.activeDropdown === dropdown ? null : dropdown;

      // Calculate max width when dropdown is opened
      if (this.active) {
        this.$nextTick(() => {
          const links = this.$el.querySelectorAll('.dropdown-content a');
          this.maxWidth = Math.max(...[...links].map(link => link.offsetWidth));
        });
      }
    },
    openTransaction(){
      var seniorIdValue = document.getElementById('senior-username-text').value;
      this.fetchProfileData(seniorIdValue);
    },
    async scanSeniorQR() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        this.$refs.video.srcObject = stream;
        this.$refs.video.play();
        this.scanFrame(stream);
      } catch (error) {
        console.error('Error accessing camera:', error);
      }
    },
    scanFrame(stream) {
      const video = this.$refs.video;
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');

      const scan = () => {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          context.drawImage(video, 0, 0, canvas.width, canvas.height);
          const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, imageData.width, imageData.height);
          if (code) {
            console.log('QR code detected:', code.data);
            // Stop the video stream
            stream.getTracks().forEach(track => track.stop());
            this.showScanner = false;
            this.senior_username = code.data;
            this.fetchProfileData(code.data);
          } else {
            requestAnimationFrame(scan);
          }
        } else {
          requestAnimationFrame(scan);
        }
      };

      scan();
    },
    async fetchProfileData(username) {
      try {
        const response = await apiServices.get(`/senior/username/ret/${username}`);
        if (response.status !== 200) {
          throw new Error('Failed to fetch senior username');
        }
        this.showInit = false;
        this.seniorProfile = true;

        
        this.profileData = response.data;
        var responseBar = await apiServices.get(`/barangay/${this.profileData.barangay_id}`);
        this.address = responseBar.data.name;
        responseBar = await apiServices.get(`/town/${responseBar.data.town_id}`);
        this.address = this.address +", "+ responseBar.data.name;

      } catch (error) {
          console.error('Error fetching profile data:', error);
          this.error = 'Failed to load profile';
          this.showInit = true;
          this.seniorProfile = false;
          this.senior_username = '';
          alert("Invalid Senior ID.");
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      const date = new Date(dateString);
      return date.toLocaleDateString(undefined, options);
    },
    proceed(){
      this.showNav = true;
      this.seniorProfile = false;
    },
    redirectToLocal(route, params){
      router.push({name: route, params: params});
    }
  }
};
</script>

<style scoped>
.scanner {
  display: flex;
  flex-direction: column;
  align-items: center;
}

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
