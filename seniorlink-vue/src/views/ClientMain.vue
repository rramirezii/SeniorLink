<template>
  <div class="client-main">
    <header class="header">
      <div class="brand">
        <h1 @click="redirectToHome">SeniorLink</h1>
      </div>
      <div class="profile-and-search">
      <!-- <div class="search-bar">
        <input type="text" placeholder="Search..." v-model="searchQuery" />
        <button @click="performSearch">Search</button>
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
      </div> 
  </header>
  <div class="total-holder">
    <div class="currency">
      <h1 id="totalamountweek">Php {{ totalAmountForWeek }}</h1> 
  </div>
    <h3>Total Discount</h3>
  </div>
  <div class="table-container">
    <button @click="goBack" class="back-button">
      <i class="fas fa-arrow-left"></i> Back to Home
    </button>
      <template v-for="(transaction, index) in sortedTransactions" :key="index">
        <h3 v-if="index === 0 || transaction.Date !== sortedTransactions[index - 1].Date" 
            class="date-header">
          {{ formatDate(transaction.Date) }}
        </h3> 
        <h4 class="establishment-header">
          {{ getEstablishmentName(transaction.Establishment) }} 
        </h4>
      <table class="table">
        <thead>
          <tr>
            <th class="wider-column">Products</th>  
            <th>Qty.</th>
            <th>Amount</th>
            <th>Balance</th> 
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, idx) in transaction.Products" :key="idx">
            <td class="wider-column">{{ product.name }}</td>
            <td>{{ product.Qty }}</td>
            <td>{{ product.amount }}</td>
            <td></td> 
          </tr>
        </tbody>
      </table>
    </template>
  </div>
  <p v-if="filteredTransactions.length === 0" class="no-results">No available data.</p>
</div>
</template>

<script>
import apiServices from "@/services/apiServices";

export default {
  props: {
    seniorId: {
      type: [String, Number],
      required: true,
    },
  },
  data() {
    return {
      tableHeaders: ["Products", "Qty.", "Amount"],
      transactions: [],
      searchQuery: "",
      loading: true,
      errorMessage: "",
      totalDiscount: 0,
      establishmentData: {}, 
    };
  },
  computed: {
    filteredTransactionsByDate() {
      const query = this.searchQuery.toLowerCase();
      return this.transactions.filter(transaction => {
        return transaction.establishment.name.toLowerCase().includes(query) || this.formatDate(transaction.date).toLowerCase().includes(query);
      });
    },
    totalAmount() {
      return this.transactions.reduce((total, transaction) => total + transaction.amount, 0).toFixed(2);
    }
  },
  async mounted() {
    try {
      const [transactionsResponse, establishmentsResponse] = await Promise.all([
        apiServices.get(`/senior/${this.seniorId}/show/barangay`),
        apiServices.get("/establishment/show/all"), 
      ]);

      if (
        transactionsResponse.status === 200 &&
        establishmentsResponse.status === 200
      ) {
        this.transactions = transactionsResponse.data.senior.transactions;
        this.establishmentData = establishmentsResponse.data.establishments.reduce(
          (acc, establishment) => {
            acc[establishment.id] = establishment.name;
            return acc;
          },
          {}
        );
        this.totalDiscount = this.calculateTotalDiscount();
      } else {
        this.errorMessage = "Error loading data.";
      }
    } catch (error) {
      console.error("Error fetching data:", error);
      this.errorMessage = "Failed to fetch data. Please try again later.";
    } finally {
      this.loading = false;
    }
  },
  methods: {
    calculateTotalDiscount() {
      return this.transactions.reduce(
        (total, transaction) => total + (transaction.discount || 0),
        0
      ).toFixed(2); // Use 0 if discount is null or undefined
    },

    getEstablishmentName(establishmentId) {
      return this.establishmentData[establishmentId] || "Unknown Establishment";
    },

    formatCurrency(value) {
      return `Php ${value.toFixed(2)}`;
    },

    formatDate(dateString) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },

    redirectToHome() {
      this.$router.push("/senior/dashboard");
    },

    goBack() {
      this.$router.push("/senior/dashboard");
    },
  },
};
</script>

<style scoped>
.client-main {
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
overflow-y: auto; /* Enable vertical scrolling */
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

.total-holder{
  padding-top: 5%;
}

.date-establishment-header {
display: flex;
align-items: flex-start; /* Align to the top */
margin-bottom: 0.5rem;  /* Add some space below the headers */
}

.date-header {
font-size: 20px; /* Smaller font size for headers */
margin-right: 1rem;   /* Space between date and establishment */
text-align: left; /* Align headers to the left */
margin-bottom: 1%;
margin-top: 8%
}

.establishment-header {
font-size: 14.5px; /* Smaller font size for headers */
margin-right: 1rem;   /* Space between date and establishment */
text-align: left; /* Align headers to the left */
margin-bottom: 0.5rem;
margin-top: 1rem;
color: #555;
}

/* Table Column Widths */
.wider-column {
width: 40%; /* Adjust as needed */
font-weight: bold;
}

.table td:not(.wider-column), 
.table th:not(.wider-column) {
width: 15%; /* Adjust as needed */
}
.total-holder {
padding-top: 10%; /* Adjust as needed */
}

.currency h1 { /* Target the h1 specifically within .currency */
font-size: 3rem; /* Increase font size significantly */
font-weight: bold;
margin-bottom: 0.5rem; /* Add space below the total */
}

.total-holder h3 { /* Style the "Total Discount" header */
font-size: 1.2rem; 
color: #555; /* Slightly darker color for the discount header */
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
