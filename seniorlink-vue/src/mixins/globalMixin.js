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

              this.$router.push({ name: componentName });
            } catch (error) {
              console.error('Error redirecting:', error);
              // Handle the error appropriately
            }
        },
        toggleProfileDropdown() {
            this.showProfileDropdown = !this.showProfileDropdown;
        },
        signOut() {
            sessionStorage.removeItem('username');
            sessionStorage.removeItem('role');
            sessionStorage.removeItem('id');
            sessionStorage.removeItem('name');
            this.$router.push({ name: 'ClientLogin' }); // Redirect to the login page
        },
        async confirmOnDelete(itemId, type, doer) {
          const username = sessionStorage.getItem('username');
          const password = prompt("Please enter your password for verification:");
          if (password === null) return; 

          try {
            const response = await apiServices.post('/validate', {username: username, password: password});
            console.log(response);
            console.log(doer);
            console.log(response.data.role === doer);
            if (response.status === 200 && response.data.role === doer){
              const confirmDelete = confirm("Are you sure you want to delete this item?");

              if(confirmDelete) {
                this.deleteItem(itemId, type, doer);
                return true;
              }

            } else {
              alert("Username or password verification failed. Please try again.");
              return false;
            }
          }catch(error) {
            console.error("Error verifying credentials:", error);
            alert("Error verifying credentials. Please try again later.");
            return false;
          }
        },
        async deleteItem(itemId, type, doer){
          try{
            const response = await apiServices.post(`/${doer}/delete`, { type:type, contents: { id: itemId } });
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
  