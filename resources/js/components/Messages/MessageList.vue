<template>
  <div>
    <md-progress-spinner md-mode="indeterminate" v-if="loading"></md-progress-spinner>
    <md-table v-if="!loading">
      <md-table-row>
        <md-table-head>Subject</md-table-head>
        <md-table-head>Sent by</md-table-head>
        <md-table-head>Start Date</md-table-head>
        <md-table-head>Expiration Date</md-table-head>
        <md-table-head>Actions</md-table-head>
      </md-table-row>

      <md-table-row v-for="message of messages" :key="message.id">
        <md-table-cell>{{ message.subject }}</md-table-cell>
        <md-table-cell>{{ message.createdBy.name }}</md-table-cell>
        <md-table-cell>{{ formatDate(message.startDate) }}</md-table-cell>
        <md-table-cell>{{ formatDate(message.expirationDate) }}</md-table-cell>
        <md-table-cell>
          <md-button class="md-primary" @click="$emit('message:view', message)">View</md-button>
          <md-button @click="$emit('message:edit', message)">Edit</md-button>
          <md-button class="md-accent" @click="remove(message)">Cancel</md-button>
        </md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import {Inject} from "vue-property-decorator";
import Message from "../../Models/Message";
import MessageService from "../../Services/MessageService";

@Component
export default class MessageList extends Vue {
  @Inject() readonly messageService!: MessageService;

  messages: Message[] = [];

  loading: boolean = false;

  async mounted() {
    await this.load();
  }

  load() {
    this.$forceUpdate();
    this.loading = true;
    this.messageService.list().then((messages: Message[]) => {
      this.messages = messages;
      this.$nextTick(() => {
        this.loading = false;
        this.$forceUpdate();
      });
    });
  }

  formatDate(date: Date | null): string {
    if (!date) {
      return '';
    }

    return `${(date.getMonth() + 1)}/${date.getDate()}/${date.getFullYear()}`;
  }


}
</script>

<style scoped>

</style>
