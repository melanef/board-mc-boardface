<template>
  <md-dialog :md-active="true">
    <md-dialog-title>Login</md-dialog-title>
    <md-dialog-content>
      <md-progress-spinner md-mode="indeterminate" v-if="loading"></md-progress-spinner>
      <form v-if="!loading" novalidate class="md-layout" @submit.prevent="validate">
        <md-field :class="getValidationClass('email')">
          <label for="email">Email</label>
          <md-input name="email" id="email" v-model="email" :disabled="sending" />
          <span class="md-error" v-if="!$v.email.required">The Email is required</span>
          <span class="md-error" v-else-if="!($v.email.email)">Invalid Email</span>
        </md-field>

        <md-field :class="getValidationClass('password')">
          <label for="password">Password</label>
          <md-input name="password" id="password" v-model="password" :disabled="sending"  type="password"/>
          <span class="md-error" v-if="!$v.password.required">The Password is required</span>
        </md-field>
      </form>
    </md-dialog-content>
    <md-dialog-actions>
      <md-button><a href="./password/reset">Forgot your password?</a></md-button>
      <md-button class="md-primary" @click="submit">Login</md-button>
    </md-dialog-actions>
  </md-dialog>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import { Inject } from "vue-property-decorator";
import { validationMixin } from "vuelidate";
import { required, email } from "vuelidate/lib/validators";
import UserService from "../Services/UserService";

@Component({
  mixins: [validationMixin],
  validations: {
    email: {
      required: required,
      email: email,
    },
    password: {
      required: required,
    },
  },
})
export default class Login extends Vue {
  @Inject() readonly userService!: UserService;

  loading: boolean = false;
  sending: boolean = false;

  email: string = '';
  password: string = '';

  getValidationClass(fieldName) {
    const field = this.$v[fieldName];

    if (field) {
      return {
        'md-invalid': field.$invalid && field.$dirty
      }
    }
  }

  submit() {
    this.sending = true;
    this.loading = true;

    this.userService.login(this.email, this.password)
      .then((result) => {
        if (!result) {
          return;
        }

        this.userService.getCurrentUser()
          .then(user => {
            this.$emit('logged', user);
          });
      });
  }
}
</script>

<style scoped>

</style>
