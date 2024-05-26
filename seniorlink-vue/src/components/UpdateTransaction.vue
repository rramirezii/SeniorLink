<template>
  <div class="update-transact">
    <header class="header">
      <div class="brand">
        <h1>SeniorLink</h1>
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
    <h2>Update Transaction</h2>
    <form @submit.prevent="handleSubmit">
      <div class="form-container">
        <div class="form-group">
        <label for="name">Product Name:</label>
        <input type="text" id="name" v-model="name" required>
      </div>
      <div class="form-group">
          <label for="quantity">Quantity:</label>
          <input 
            type="number" 
            id="quantity" 
            v-model="quantity" 
            min="0"                
            @input="handleNumberInput('quantity')"
          >
        </div>
        <div class="form-group">
          <label for="price">Price:</label>
          <input 
            type="number" 
            id="price" 
            v-model="price" 
            min="0"                
            @input="handleNumberInput('price')"
          >
        </div>
      <div class="form-group">
        <label for="name">Attendant:</label>
        <input type="text" id="name" v-model="name" required>
      </div>
      <div class="form-group">
        <label for="password">Enter Password:</label>
        <input type="password" id="password" v-model="password" required>
      </div>
    </div>
      <div class="form-actions">
        <button type="submit">Update Transaction</button>
      </div>
    </form>
  
  </div>
</template>

<script>
import apiServices from "@/services/apiServices";
import { useRouter } from 'vue-router';

export default {
  setup() {
    const router = useRouter();
    return { router };
  },
  props: {
    transactionId: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      name: '',
      quantity: 0,
      price: 0,
      attendant: '', 
      password: '', 
      showSuccessMessage: false,
      showErrorMessage: false,
      errorMessage: "",
    };
  },
  mounted() {
    // Fetch the existing transaction data
    apiServices.get(`/establishment/show/transaction/${this.transactionId}`)
      .then(response => {
        if (response.status === 200) {
          const product = response.data.product; 
          this.name = product.name;
          this.quantity = product.quantity;
          this.price = product.price;
          this.attendant = response.data.transaction.attendant; // Assuming your API returns this field now
        } else {
          this.errorMessage = "Error loading transaction details.";
          this.showErrorMessage = true;
        }
      })
      .catch(error => {
        console.error('Error fetching transaction details:', error);
        this.errorMessage = "Error loading transaction details.";
        this.showErrorMessage = true;
      });
  },
  methods: {
    handleNumberInput(field) {
      this[field] = Math.max(0, this[field]); 
    },

    async handleSubmit() {
      this.showSuccessMessage = false;
      this.showErrorMessage = false;
      this.errorMessage = "";

      try {
        // Input validation
        if (!this.name || this.quantity <= 0 || this.price <= 0 || !this.attendant || !this.password) {
          this.errorMessage = "Please fill in all fields with valid values.";
          this.showErrorMessage = true;
          return;
        }

        const response = await apiServices.post(
          `/establishment/update/transaction`,
          {
            id: this.transactionId,
            product: {
              name: this.name,
              quantity: this.quantity,
              price: this.price
            },
            attendant: this.attendant, // Include the updated attendant
            password: this.password,
          }
        );
      if (response.status === 200) {
          console.log("Transaction updated successfully:", response.data);
          this.showSuccessMessage = true;

          setTimeout(() => {
            this.router.push({ name: "EstablishmentDashboard" });
          }, 1500);
        } else if (response.status === 401) {
          // Specifically handle unauthorized access (e.g., wrong password)
          this.errorMessage = response.data.message || "Unauthorized.";
          this.showErrorMessage = true;
        } else {
          this.errorMessage = "Error updating transaction: " + response.data.message;
          this.showErrorMessage = true;
        }
      } catch (error) {
        this.showErrorMessage = true;
        this.errorMessage = "An error occurred. Please try again later.";
        console.error("Error:", error);
      }
    },
  },
};
</script>

<style scoped>
.update-transact {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem;
}

label {
  display: block;
  margin: 1rem;
  font-weight: bold;
  text-align: left;
  width: 150px;
}

form input {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin: 0.5rem;
  flex: 1;            /* Allow input to take up remaining space */
}
label, input {
  float: left;
}
button {
  padding: 1em;
  background-color: #2c3e50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
  font-weight: bold;
}


.form-group {
  display: flex; 
  align-items: center;  /* Vertically center label and input */
  width: 600px; 
}

.form-group label {
  width: 150px;      /* Set a fixed width for the labels */
  text-align: right; /* Align the label text to the right */
  margin-right: 1rem; /* Add some space between label and input */
}

/* Center the form elements within the form-container */
.form-container {
  display: flex;          
  flex-direction: column; 
  align-items: center;
  padding-right: 25%;
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
