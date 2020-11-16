import axios from "axios";
import User from "../Models/User";
import Urls from "../Urls";

export default class UserService {
  getCurrentUser(): Promise<User> {
    return axios.get(Urls.USERS.READ)
      .then(response => new User(response.data))
  }

  login(email: string, password: string): Promise<boolean> {
    return axios.post(Urls.USERS.LOGIN,{
        email,
        password,
      })
      .then(response => {
        if (response.status === 200) {
          window.location.reload();
        }

        return false;
      });
  }

  logout(): Promise<void> {
    return axios.get(Urls.USERS.LOGOUT)
      .then(response => {
        window.location.reload();
      });
  }
}
