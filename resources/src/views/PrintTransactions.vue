<template>
  <div class="client-print">
    <header class="header">
      </header>
    
    <div class="filter">
      <label for="start-date">Start Date:</label>
      <input type="date" id="start-date" v-model="startDate">
      <label for="end-date">End Date:</label>
      <input type="date" id="end-date" v-model="endDate">
      <button @click="filterData">Filter</button>
      <button @click="generatePDF">Print to PDF</button>
    </div>

    <div id="pdf-content" v-show="false">
      <div class="total-holder">
        <div class="currency">
          <h1 id="totalamountweek">Php {{ totalAmountForWeek }}</h1>
        </div>
        <h3>Total Discount</h3>
      </div>
      <div class="table-container">
        <template v-for="(dateGroup, date) in groupedTableData" :key="date">
          <h3 class="date-header">{{ formatDate(date) }}</h3>
          <div v-for="(establishmentGroup, establishment) in dateGroup" :key="establishment">
            <h4 class="establishment-header">{{ establishment }}</h4>
            <table class="table">
              <thead>
                <tr>
                  <th v-for="header in tableHeaders" :key="header" :class="{ 'wider-column': header === 'Commodities' }">
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in establishmentGroup" :key="item.id">
                  <td v-for="header in tableHeaders" :key="header" :class="{ 'wider-column': header === 'Commodities' }">
                    {{ item[header] }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>
    </div>

    <div v-show="filteredTableData.length > 0">  
      <div class="total-holder">
        <div class="currency">
          <h1 id="totalamountweek">Php {{ totalAmountForWeek }}</h1>
        </div>
        <h3>Total Discount</h3>
      </div>
      <div class="table-container">
        <template v-for="(dateGroup, date) in groupedTableData" :key="date">
          <h3 class="date-header">{{ formatDate(date) }}</h3>
          <div v-for="(establishmentGroup, establishment) in dateGroup" :key="establishment">
            <h4 class="establishment-header">{{ establishment }}</h4>
            <table class="table">
              <thead>
                <tr>
                  <th v-for="header in tableHeaders" :key="header" :class="{ 'wider-column': header === 'Commodities' }">
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in establishmentGroup" :key="item.id">
                  <td v-for="header in tableHeaders" :key="header" :class="{ 'wider-column': header === 'Commodities' }">
                    {{ item[header] }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>
    </div>

    <p v-if="filteredTableData.length === 0" class="no-results">No results found.</p>
  </div>
</template>

<script>
import axios from 'axios';
import jsPDF from 'jspdf';

export default {
  data() {
    return {
      tableData: [],
      tableHeaders: [],
      startDate: '',
      endDate: '',
      filteredData: [],
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      const response = await axios.get('/your-data-endpoint.json');
      this.tableData = response.data;
      this.tableHeaders = Object.keys(this.tableData[0]); // Extract headers
      this.filteredData = [...this.tableData]; // Initialize filteredData
    },
    filterData() {
      const start = new Date(this.startDate);
      const end = new Date(this.endDate);
      this.filteredData = this.tableData.filter(item => {
        const itemDate = new Date(item.Date); 
        return itemDate >= start && itemDate <= end;
      });
    },
    generatePDF() {
      const printContent = document.getElementById('pdf-content').cloneNode(true); // Clone the content to be printed
      printContent.style.display = 'block'; // Make it visible
      const doc = new jsPDF();
      doc.html(printContent, {
        callback: (doc) => {
          doc.save('filtered_data.pdf');
        },
        x: 10,
        y: 10,
      });
    },
  },
};
</script>