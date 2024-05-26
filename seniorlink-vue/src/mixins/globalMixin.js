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
        async confirmOnDelete(itemId, type) {
          const username = prompt("Please enter your username for verification:");
          if (username === null) return;

          const password = prompt("Please enter your password for verification:");
          if (password === null) return; 

          try {
            const response = await apiServices.post('/validate', {username: username, password: password});
            
            if (response.status === 200 && response.data.role === 'admin'){
              const confirmDelete = confirm("Are you sure you want to delete this item?");

              if(confirmDelete) {
                this.deleteItem(itemId, type);
                this.refreshData(); 
              }

            } else {
              alert("Username or password verification failed. Please try again.");
            }
          }catch(error) {
            console.error("Error verifying credentials:", error);
            alert("Error verifying credentials. Please try again later.");
          }
        },
        async deleteItem(itemId, type){
          try{
            const response = await apiServices.post('/admin/delete', { type:type, contents: { id: itemId } });
            console.log(response);
            alert('Item deleted successfully');
          }catch(error){
            console.error('Error deleting item:', error);
            alert('Error deleting item. Please try again later.');
          }
        },
        saveToJson(data) {
          //for saving data
          localStorage.setItem('apiData', JSON.stringify(data));
        }
    }
  };
  