<template>
  <div class="update-client">
    <header class="header">
      <div class="brand">
        <h1 @click="redirectToHome">SeniorLink</h1>
      </div>
      <!-- <div class="search-bar">
        <input type="text" placeholder="Search..." />
        <button>Search</button>
      </div> -->
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
    <h2>Update Client Account</h2>
    <form @submit.prevent="handleSubmit">
      <div class="form-container">
        <div class="form-group">
          <label for="contactNumber">Contact Number:</label>
          <input type="tel" id="contactNumber" v-model="contactNumber">
        </div>

        <div class="form-group">
          <label for="profilePic">Profile Picture:</label>
          <input type="file" id="profilePic" accept="image/*" @change="handleFileChange">
        </div>

        <div v-if="profileImagePreview" class="image-preview">
          <img :src="profileImagePreview" alt="Profile Preview" />
        </div>
      </div>
      <div class="form-actions">
        <button type="submit">Update Information</button>
      </div>
      
    </form>
  
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    clientId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      contactNumber: '',
      profileImage: null, // File object for the uploaded image
      profileImagePreview: null, // Data URL for previewing the image
    };
  },
  methods: {
    handleFileChange(event) {
      const file = event.target.files[0];
      this.profileImage = file;

      // Create a preview
      const reader = new FileReader();
      reader.onload = (e) => {
        this.profileImagePreview = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    async handleSubmit() {
      const formData = new FormData(); // Use FormData to send file data
      formData.append('contactNumber', this.contactNumber);
      if (this.profileImage) {
        formData.append('profilePic', this.profileImage);
      }

      try {
        const response = await axios.post(`/api/clients/${this.clientId}/update`, formData); // Send client ID in the URL

        if (response.status === 200) {
          console.log('Client updated successfully:', response.data);
          // Optionally, navigate or display a success message
        } else {
          console.error('Error updating client:', response.data);
          // Handle errors gracefully (e.g., show error messages)
        }
      } catch (error) {
        console.error('Error:', error);
      }
    },
    redirectToHome() {
    this.$router.push('/senior/dashboard'); // Use the path directly
  },
  },
};
</script>

<style scoped>
.update-client {
  padding: 1rem;
  width: 90%;
  max-width: 400px;
}

h1, h2 {  
  font-weight: bold;
  text-align: center; /* Centered titles */
}

h2 {
  margin-bottom: 1rem;
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.form-container {
  width: 100%;
}

.form-group {
  display: block;
  margin-bottom: 2rem;
  width: 100%; /* Ensure the form group takes the full width */
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  text-align: left;
}

form input[type="tel"],
form input[type="file"] {
  width: calc(100% - 1rem); /* Full width with some margin */
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box; 
}

button[type="submit"] {
  width: 100%;
  padding: 1rem;
  background-color: #2c3e50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  margin-top: 2rem;
}

.image-preview {
  margin-top: 1rem;
  max-width: 100%;
}

.image-preview img {
  max-width: 100%;
  height: auto;
}
/* Header styles */
.header {
  position: fixed; 
  top: 0;
  left: 0;
  width: 100%; 
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff; 
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
  z-index: 10;
}

.brand {
  padding-left: 5%;
}

/* Profile styles */
.profile-placeholder {
  width: 55px;
  height: 55px;
  background-color: #d3d3d3; 
  border-radius: 10%;
  cursor: pointer;
  transition: background-color 0.25s;
  display: inline-flex; 
  margin-right: 2rem;
  margin-top: 1ex;
}

.profile-placeholder:hover {
  background-color: #808080; 
}

.profile-container {
  position: relative; 
}


/* Media Query for Smaller Screens (e.g., phones) */
@media (max-width: 600px) {
  h2 {
    font-size: 30px;
    margin-top: 4rem;
    margin-bottom: 5rem;
  }

  label {
    font-size: 1rem;
    font-weight: bold;
  }

  form input[type="tel"],
  form input[type="file"] {
    font-size: 0.9rem;
  }
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
