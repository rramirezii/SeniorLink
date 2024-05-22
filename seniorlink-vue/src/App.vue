<template>
    <router-view/>
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
  },
  methods: {
    toggleProfileDropdown() {
      this.showProfileDropdown = !this.showProfileDropdown;
    },
    signOut() {
      // Implement your sign-out logic here
      // (e.g., clear tokens, redirect to login page)
      console.log("Signing out...");
    },
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
    },
    async redirectTo(routeName, payload = null) {
      try {
        const redirectUrl = process.env[`VUE_APP_${routeName.toUpperCase()}_URL`];
        const componentName = routeName;

        if (payload) {
          console.log("Payload:", payload);
          // Make the API call with payload if provided
          const response = await apiServices.post(redirectUrl, payload);
          // Handle the API response here (e.g., save to JSON, update state, etc.)
          console.log("API Response:", response);
          this.saveToJson(response.data);
        }
        console.log(this.$router.getRoutes());
        // Redirect to the corresponding Vue file
        this.$router.push({ name: componentName });
        console.log("Navigation successful.");
      } catch (error) {
        console.error('Error redirecting:', error);
        // Handle the error appropriately
      }
  }

  }
};
</script>

<style scoped>
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
