<template>
  <div>
    <select id="yearSelect"
            v-model="yearData"
            @change="update">
      <option v-for="year in this.years"
              :key="year"
              :value="year"
              :selected="year === yearData">
        {{ year }}
      </option>
    </select>年
    <select id="monthSelect"
            v-model="monthData"
            @change="update">
      <option v-for="month in this.months"
              :key="month"
              :value="month"
              :selected="month === monthData">
        {{ month | padding }}
      </option>
    </select>月
    <select id="dateSelect"
            v-model="dateData"
            @change="update">
      <option v-for="date in this.dates(this.yearData, this.monthData)"
              :key="date"
              :value="date"
              :selected="date === dateData">
        {{ date | padding }}
      </option>
    </select>日
  </div>
</template>
<script lang="ts">
  import {Component, Prop, Vue, Emit} from 'vue-property-decorator';
  import moment from 'moment';

  @Component({
    filters: {
      padding(value: number): string | null {
        if (value === null) {
          return null;
        }
        return ('00' + value).slice(-2);
      },
    },
  })
  export default class DateSelector extends Vue {
    // data
    yearData: number = this.yearValue;
    monthData: number = this.monthValue;
    dateData: number | null = this.dateValue;
    alert: boolean = false;

    @Prop()
    value!: string | Date | null;

    // computed
    get years(): number[] {
      const goBackYears = 90;
      const currentYear = moment().year();
      const startYear = currentYear - goBackYears;
      return [...Array(goBackYears + 1).keys()].map((x) => x + startYear);
    }

    get months(): number[] {
      return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    }

    get yearValue(): number {
      if (!this.value) {
        return moment().year();
      }
      return moment(this.value).year();
    }

    get monthValue(): number {
      if (!this.value) {
        return moment().month() + 1;
      }
      return moment(this.value).month() + 1;
    }

    get dateValue(): number | null {
      if (!this.value) {
        return null;
      }
      return moment(this.value).date();
    }

    // method
    dates(year: number, month: number): number[] {
      const maxDate = this.getFinalDate(year, month);
      return [...Array(maxDate).keys()].map((x) => x + 1);
    }

    getFinalDate(year: number, month: number): number {
      return moment([year, month - 1]).endOf('month').date();
    }

    @Emit('input')
    update(event: Event): Date | null {
      if (!this.dateData) {
        return null;
      }
      if (!moment([this.yearData, this.monthData - 1, this.dateData]).isValid()) {
        this.dateData = this.getFinalDate(this.yearData, this.monthData);
      }
      if (moment([this.yearData, this.monthData - 1, this.dateData]).isValid()) {
        return moment([this.yearData, this.monthData - 1, this.dateData]).toDate();
      }
      return null;
    }
  }
</script>
