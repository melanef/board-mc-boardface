<template>
  <md-dialog :md-active="true">
    <md-dialog-content>
      <md-card>
        <md-card-header>
          <div class="md-title">{{ message.subject }}</div>
        </md-card-header>

        <md-card-content>
          <p><i>by </i><strong>{{ message.createdBy.name}}</strong> ({{ message.createdBy.email }})</p>
          <p>{{ message.content }}</p>
          <p>
            <i>Start Date: {{ formatDate(message.startDate) }}</i>
          </p>
          <p v-if="message.expirationDate">
            <i>Expiration Date: {{ formatDate(message.expirationDate) }}</i>
          </p>

        </md-card-content>

        <md-card-actions>
          <md-button @click="$emit('close')">Close</md-button>
          <md-button class="md-accent" @click="$emit('message:remove', message)">Cancel</md-button>
          <md-button class="md-primary" @click="$emit('message:edit', message)">Edit</md-button>
        </md-card-actions>
      </md-card>
    </md-dialog-content>
  </md-dialog>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import { Prop } from "vue-property-decorator";
import Message from "../../Models/Message";

@Component
export default class MessageView extends Vue {
  @Prop(Message) readonly message: Message;

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
