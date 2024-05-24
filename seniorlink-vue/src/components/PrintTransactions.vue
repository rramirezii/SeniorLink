<template>
  <div class="print-transactions">
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
  <div class="date-range-filter">
        <label for="startDate" class="start-date-label">Start Date:</label>
        <input type="date" id="startDate" v-model="startDate" class="date-input">
        <label for="endDate" class="end-date-label">End Date:</label>
        <input type="date" id="endDate" v-model="endDate" class="date-input">
      </div>
    <button @click="generatePDF" class="print-button">Print Transactions</button>

    <div class="total-holder">
    </div>
    
    <div class="table-container">
      <template v-for="(transaction, index) in filteredTransactionsByDate" :key="index">
        <div class="date-establishment-header">
          <h3 v-if="index === 0 || transaction.Date !== filteredTransactionsByDate[index - 1].Date" class="date-header">
            {{ formatDate(transaction.Date) }}
          </h3>
          <h4 class="establishment-header">
            {{ getEstablishmentName(transaction.Establishment) }}
          </h4>
        </div>
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
    <button @click="goBack" class="back-button">
      <i class="fas fa-arrow-left"></i> Back to Home
    </button>
    <p v-if="filteredTransactionsByDate.length === 0" class="no-results">No available data.</p>
  </div>
</template>

<script>
import axios from "axios";
import { jsPDF } from "jspdf";

export default {
  data() {
    return {
      tableHeaders: ["Products", "Qty.", "Amount", "Balance"],
      transactions: [],
      searchQuery: "",
      loading: true,
      establishmentData: {},
      startDate: "",
      endDate: "",
    };
  },

  props: {
    seniorId: {
      type: [String, Number],
      required: true,
    },
  },

  computed: {
    filteredTransactionsBySenior() {
      return this.sortedTransactions.filter(
        (transaction) => transaction.seniorId === this.seniorId
      );
    },

    filteredTransactions() {
      const query = this.searchQuery.toLowerCase();
      return this.transactions.filter((transaction) => {
        return Object.values(transaction).some((value) => {
          if (Array.isArray(value)) {
            return value.some((item) =>
              Object.values(item).some((val) =>
                String(val).toLowerCase().includes(query)
              )
            );
          }
          return String(value).toLowerCase().includes(query);
        });
      });
    },

    sortedTransactions() {
      return [...this.filteredTransactions].sort((a, b) => {
        const dateA = new Date(a.Date);
        const dateB = new Date(b.Date);
        return dateB - dateA; // Sort descending by date
      });
    },

    filteredTransactionsByDate() {
      const start = new Date(this.startDate);
      const end = new Date(this.endDate);
      end.setDate(end.getDate() + 1); // Include the end date

      return this.sortedTransactions.filter((transaction) => {
        const transactionDate = new Date(transaction.Date);
        return transactionDate >= start && transactionDate < end;
      });
    },

    totalAmountForWeek() {
      const totalAmount = this.filteredTransactionsByDate.reduce((total, item) => {
        const amount = item.Products.reduce(
          (productTotal, product) => productTotal + parseFloat(product.amount),
          0
        );
        return total + amount;
      }, 0);
      return totalAmount.toFixed(2);
    },
  },

  mounted() {
    const today = new Date();
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(today.getDate() - 7);

    this.startDate = oneWeekAgo.toISOString().slice(0, 10);
    this.endDate = today.toISOString().slice(0, 10);

    axios
      .get("/transactions.json") // Assuming transactions data is in a JSON file
      .then((response) => {
        this.transactions = response.data;
      })
      .catch((error) => {
        console.error("Error fetching transactions:", error);
      });

    axios
      .get("/establishment.json") // Assuming establishment data is in a JSON file
      .then((response) => {
        this.establishmentData = response.data;
      })
      .catch((error) => {
        console.error("Error fetching establishment data:", error);
      });
  },

  methods: {
    getEstablishmentName(establishment) {
      return this.establishmentData[establishment[0]]?.name || establishment[0];
    },

    formatDate(dateString) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      const date = new Date(dateString);
      return date.toLocaleDateString(undefined, options);
    },

    generatePDF() {
      const doc = new jsPDF();
      // ... Add title, table headers, and table data
      doc.save("transaction_report.pdf");
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
.print-transactions {
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
form input {
  padding: 1rem;
  padding-left: 5rem;
  padding-right: 5rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin: 0.5rem 0;
  flex: 1;            /* Allow input to take up remaining space */
  box-sizing: border-box;
}

.form-group {
  display: flex; 
  align-items: center;  /* Vertically center label and input */
  width: 400px; 
}

.form-group label {
  width: 100px;      /* Set a fixed width for the labels */
  text-align: right; /* Align the label text to the right */
  margin-right: 1rem; /* Add some space between label and input */
}

/* Adjusted Styles for date input and label */
.date-input {
  padding: 1rem;
  padding-left: 1em;
  padding-right: 1em;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin: 0.5rem 0;
  flex: 1; /* Allow input to take up remaining space */
  box-sizing: border-box;
}


.date-range-filter {
  margin-top: 2rem;
}

label{
  margin-left: 2rem;
  margin-right: 1rem;
}

button {
  background-color: #2c3e50;
  color: white;
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  width: auto;
  font-weight: bold;
  margin-top: 2rem;
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
