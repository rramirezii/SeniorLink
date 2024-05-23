// global js functions for the vue

import apiServices from '@/services/apiServices';

export const globalMixin = {
    methods: {
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
        },
        toggleProfileDropdown() {
            this.showProfileDropdown = !this.showProfileDropdown;
          },
        signOut() {
        // Implement your sign-out logic here
        // (e.g., clear tokens, redirect to login page)
        console.log("Signing out...");
        },
    }
  };
  