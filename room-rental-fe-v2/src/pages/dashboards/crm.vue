<script setup lang="ts">
import CrmActiveProject from '@/views/dashboards/crm/CrmActiveProject.vue'
import CrmActivityTimeline from '@/views/dashboards/crm/CrmActivityTimeline.vue'
import CrmAnalyticsSales from '@/views/dashboards/crm/CrmAnalyticsSales.vue'
import CrmBrowserStates from '@/views/dashboards/crm/CrmBrowserStates.vue'
import CrmEarningReportsYearlyOverview from '@/views/dashboards/crm/CrmEarningReportsYearlyOverview.vue'
import CrmProjectStatus from '@/views/dashboards/crm/CrmProjectStatus.vue'
import CrmRecentTransaction from '@/views/dashboards/crm/CrmRecentTransaction.vue'
import CrmRevenueGrowth from '@/views/dashboards/crm/CrmRevenueGrowth.vue'
import CrmSalesAreaCharts from '@/views/dashboards/crm/CrmSalesAreaCharts.vue'
import CrmSessionsBarWithGapCharts from '@/views/dashboards/crm/CrmSessionsBarWithGapCharts.vue'

const simpleStatisticsDemoCards = [
  {
    icon: 'tabler-currency-dollar',
    color: 'error',
    title: 'Doanh số tháng này',
    subTitle: 'Last week',
    stat: '1.28k',
    change: '-12.2%',
  },
  {
    icon: 'tabler-chart-bar',
    color: 'info',
    title: 'Tổng số bài viết',
    subTitle: 'Last week',
    stat: '12',
  },
]
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="4"
      sm="6"
      lg="2"
    >
      <CrmSalesAreaCharts />
    </VCol>

    <!--    <VCol -->
    <!--      cols="12" -->
    <!--      md="4" -->
    <!--      sm="6" -->
    <!--      lg="2" -->
    <!--    > -->
    <!--      <CrmSessionsBarWithGapCharts /> -->
    <!--    </VCol> -->

    <VCol
      v-for="demo in simpleStatisticsDemoCards"
      :key="demo.title"
      cols="12"
      sm="6"
      md="4"
      lg="2"
    >
      <VCard>
        <VCardText>
          <VAvatar
            :color="demo.color"
            variant="tonal"
            rounded
            size="42"
          >
            <VIcon :icon="demo.icon" />
          </VAvatar>

          <h6 class="text-h6 mt-3">
            {{ demo.title }}
          </h6>
          <p class="text-sm text-disabled mt-1 mb-0">
            {{ demo.subTitle }}
          </p>
          <p class="my-2">
            {{ demo.stat }}
          </p>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 Revenue Growth -->
    <!--    <VCol -->
    <!--      cols="12" -->
    <!--      md="8" -->
    <!--      lg="4" -->
    <!--    > -->
    <!--      <CrmRevenueGrowth /> -->
    <!--    </VCol> -->

    <!-- 👉 Earning Reports -->
    <!--    <VCol -->
    <!--      cols="12" -->
    <!--      md="8" -->
    <!--    > -->
    <!--      <CrmEarningReportsYearlyOverview /> -->
    <!--    </VCol> -->

    <!-- 👉 Sales -->
    <!--    <VCol -->
    <!--      cols="12" -->
    <!--      md="4" -->
    <!--    > -->
    <!--      <CrmAnalyticsSales /> -->
    <!--    </VCol> -->

    <!-- 👉 Active timeline -->
    <!--    <VCol -->
    <!--      cols="12" -->
    <!--      md="6" -->
    <!--    > -->
    <!--      <CrmActivityTimeline /> -->
    <!--    </VCol> -->
  </VRow>
</template>
