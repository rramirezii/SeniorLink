<template>
    <div> 
        <h2>Create Town Account</h2>
        <form @submit.prevent="handleSubmit">
            <div class="form-container">
            <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" v-model="name" required>
            </div>
            <div class="form-group">
            <label for="zipcode">Zip Code:</label>
            <input type="number" id="zipcode" v-model="zipcode" required>
            </div>
            <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="password" required>
            </div>
        </div>
            <div class="form-actions">
            <button type="submit">Create Account</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
    data() {
        return {
        name: '',
        zipCode: '',
        password: '',
        };
    },
    };
</script>

<style scoped>
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
</style>