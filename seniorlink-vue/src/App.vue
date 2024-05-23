<template>
    <router-view/>
</template>

<script>

export default {
  data() {
    return {
      activeDropdown: null, // Track the currently active dropdown
      maxWidth: 0,
      showProfileDropdown: false, // New property for the profile dropdown
    };
  },
  methods: {
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
