<template>
    <div class="login-web">
      <header class="header">
        <div class="brand">
          <h1>SeniorLink</h1>
        </div>
      </header>
      <h2>Log-In</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-container">
          <div class="form-group">
          <label for="ID">ID:</label>
          <input type="text" id="ID" v-model="ID" required>
        </div>
      </div>
        <div class="form-actions">
          <button type="submit">Enter</button>
        </div>
      </form>
    
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        ID: ''
      };
    },
  };
  </script>
  
  <style scoped>
  
  .login-web {
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
    padding-left: 44%;
  }
  
  .logo {
    font-size: 1.5rem;
    font-weight: bold;
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
  