<script setup>
import CargoStatusWidget from '@/components/dashboard/CargoStatusWidget.vue';
import CustomerSatisfactionWidget from '@/components/dashboard/CustomerSatisfactionWidget.vue';
import GeneralSalesWidget from '@/components/dashboard/GeneralSalesWidget.vue';
import ReceivedReviewsWidget from '@/components/dashboard/ReceivedReviewsWidget.vue';
import StockStatusWidget from '@/components/dashboard/StockStatusWidget.vue';
</script>

<template>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-7">
            <GeneralSalesWidget />
        </div>
        <div class="col-span-12 xl:col-span-5">
            <CustomerSatisfactionWidget />
        </div>
        <div class="col-span-12 md:col-span-6 xl:col-span-4">
            <StockStatusWidget />
        </div>
        <div class="col-span-12 md:col-span-6 xl:col-span-4">
            <ReceivedReviewsWidget />
        </div>
        <div class="col-span-12 md:col-span-6 xl:col-span-4">
            <CargoStatusWidget />
        </div>
    </div>
</template>
