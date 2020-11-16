<template>
  <md-dialog :md-active="true">
    <md-dialog-title>{{ title }}</md-dialog-title>
    <md-dialog-content>
      <md-progress-spinner md-mode="indeterminate" v-if="loading"></md-progress-spinner>
      <form v-if="!loading" novalidate class="md-layout" @submit.prevent="validate">
        <md-field :class="getValidationClass('subject')">
          <label for="subject">Subject</label>
          <md-input name="subject" id="subject" v-model="message.subject" :disabled="sending" />
          <span class="md-error" v-if="!$v.message.subject.required">The subject is required</span>
          <span class="md-error" v-else-if="!($v.message.subject.minlength && $v.message.subject.maxlength)">Invalid subject</span>
        </md-field>

        <md-field :class="getValidationClass('content')">
          <label for="content">Content</label>
          <md-textarea name="content" id="content" v-model="message.content" :disabled="sending"></md-textarea>
          <span class="md-error" v-if="!$v.message.content.required">The content is required</span>
          <span class="md-error" v-else-if="!($v.message.content.minlength && $v.message.content.maxlength)">Invalid content</span>
        </md-field>
        <div class="md-layout">
          <div class="md-layout-item">
            <md-datepicker v-model="message.startDate" md-immediately :class="getValidationClass('startDate')">
              <label>Start Date</label>
              <span class="md-error" v-if="!$v.message.startDate.required">The Start Date is required</span>
              <span class="md-error" v-if="!$v.message.startDate.minValue">The Start Date must be in the future</span>
            </md-datepicker>
          </div>
          <div class="md-layout-item">
            <md-datepicker v-model="message.expirationDate" md-immediately :class="getValidationClass('expirationDate')">
              <label>Expiration Date</label>
              <span class="md-error" v-if="!$v.message.expirationDate.minValue">The Expiration Date must be in the future</span>
            </md-datepicker>
          </div>
        </div>
      </form>
    </md-dialog-content>

    <md-dialog-actions v-if="!loading">
      <md-button class="md-primary" @click="$emit('close')">Close</md-button>
      <md-button class="md-primary" @click="validate() && submit()">Save</md-button>
    </md-dialog-actions>
  </md-dialog>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import { Inject, Prop } from "vue-property-decorator";
import { validationMixin } from "vuelidate";
import { required, minLength, maxLength, minValue } from "vuelidate/lib/validators";
import Message from "../../Models/Message";
import MessageService from "../../Services/MessageService";

@Component({
  mixins: [validationMixin],
  validations: {
    message: {
      subject: {
        required: required,
        minLength: minLength(5),
        maxLength: maxLength(250),
      },
      content: {
        required: required,
        minLength: minLength(10),
        maxLength: maxLength(1000),
      },
      startDate: {
        required: required,
        minValue: minValue(new Date()),
      },
      expirationDate: {
        minValue: minValue(new Date()),
      },
    }
  },
})
export default class MessageForm extends Vue {
  @Inject() readonly messageService!: MessageService;

  @Prop(Number) readonly messageId: number | null;

  message: Message = new Message();

  sending: boolean = false;
  loading: boolean = true;

  mounted() {
    if (!this.messageId) {
      this.loading = false;
      return;
    }

    this.messageService.read(this.messageId)
      .then(message => {
        this.message = message;
        this.loading = false;
      });
  }

  get title() {
    return this.messageId ? 'Edit Message' : 'New Message';
  }

  getValidationClass(fieldName) {
    const field = this.$v.message[fieldName];

    if (field) {
      return {
        'md-invalid': field.$invalid && field.$dirty
      }
    }
  }

  validate() {
    this.$v.$touch();

    return !this.$v.$invalid;
  }

  submit() {
    this.sending = true;
    let deferred: Promise<Message>;

    if (!!this.messageId) {
      deferred = this.messageService.update(this.message);
    } else {
      deferred = this.messageService.create(this.message);
    }

    deferred.then(() => {
      this.$emit('submit', this.message);
    });
  }
}
</script>

<style scoped>

</style>
