<template>
  <div class="senior-link">
    <header class="header">
      <div class="brand">
        <h1>SeniorLink</h1>
      </div>
      <div class="search-bar">
        <!-- <input type="text" placeholder="Search..." />
        <button>Search</button> -->
      </div>
      <div class="profile">
        <router-link to="/profile" class="profile-button">
        <i class="fas fa-user">Profile</i> 
      </router-link>
      </div>
    </header>
    <nav>
      <ul class="nav-buttons">
        <li 
          v-for="menu in menus" 
          :key="menu.name" 
          @mouseover="openSubMenu(menu.name)" 
          @mouseleave="closeSubMenu(menu.name)" 
          class="dropdown"
          :class="{ active: activeSubMenu === menu.name }"
        >
          {{ menu.name }}
          <ul v-if="activeSubMenu === menu.name" class="dropdown-content">
            <li v-for="item in menu.items" :key="item.name" class="dropdown-submenu">
              {{ item.name }}
              <div v-if="activeSubMenu === menu.name" class="submenu-content">
                <router-link 
                  v-for="subItem in item.subItems" 
                  :key="subItem.path" 
                  :to="subItem.path"
                  @click.prevent="redirectTo(subItem.path)"
                >
                  {{ subItem.name }}
                </router-link>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeSubMenu: null, // Track the currently active dropdown
      maxWidth: 0,
      menus: [
        {
          name: 'Create Account',
          items: [
            { name: 'Super Admin', subItems: [{ name: 'Create Super Admin', path: 'CreateSuper' }] },
            { name: 'Town', subItems: [{ name: 'Create Town', path: 'CREATE_TOWN' }] },
            { name: 'Establishment', subItems: [{ name: 'Create Establishment', path: 'CREATE_ESTABLISHMENT' }] }
          ]
        },
        {
          name: 'View Account',
          items: [
            { name: 'Towns', subItems: [{ name: 'View Towns', path: 'VIEW_TOWN' }] },
            { name: 'Barangay', subItems: [{ name: 'View Barangay', path: 'VIEW_BARANGAY' }] },
            { name: 'Clients', subItems: [{ name: 'View Clients', path: 'VIEW_CLIENT' }] },
            { name: 'Super Admin', subItems: [{ name: 'View Super Admin', path: 'view-super' }] },
            { name: 'Establishment', subItems: [{ name: 'View Establishment', path: 'view-establish' }] }
          ]
        },
        {
          name: 'Update Account Info',
          items: [
            { name: 'Towns', subItems: [{ name: 'Update Town', path: '/update-town' }] },
            { name: 'Establishment', subItems: [{ name: 'Update Establishment', path: '/update-establish' }] },
            { name: 'Super Admin', subItems: [{ name: 'Update Super Admin', path: '/update-super' }] }
          ]
        },
        {
          name: 'Delete Account',
          items: [
            { name: 'Towns', subItems: [{ name: 'Delete Town', path: '/delete-town' }] },
            { name: 'Establishment', subItems: [{ name: 'Delete Establishment', path: '/delete-establish' }] },
            { name: 'Super Admin', subItems: [{ name: 'Delete Super Admin', path: '/delete-super' }] }
          ]
        }
      ]
    };
  },
  methods: {
    openSubMenu(menuName) {
      this.activeSubMenu = menuName;
    },
    closeSubMenu() {
  const submenu = this.$el.querySelector('.submenu-content');
  if (submenu) {
    submenu.classList.add('fade-out');
    setTimeout(() => {
      this.activeSubMenu = null;
      submenu.classList.remove('fade-out');
    }, 300); // Delay to match transition duration
  }
}
  }
};
</script>


<style scoped>
.senior-link {
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
