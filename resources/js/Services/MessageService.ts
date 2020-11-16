import axios from "axios";
import Message from "../Models/Message";
import Urls from "../Urls";
import UserService from "./UserService";

export default class MessageService {
  private userService: UserService;

  public constructor(userService: UserService) {
    this.userService = userService;
  }

  public async list(): Promise<Array<Message>> {
    return axios.get(Urls.MESSAGES.LIST)
      .then(response => response.data)
      .then(data => {
        const messages: Message[] = [];
        for (const body of data) {
          messages.push(new Message(body));
        }

        return messages;
      });
  }

  public async read(id: number | string): Promise<Message> {
    return axios.get(`${Urls.MESSAGES.READ}${id}`)
      .then(response => new Message(response.data));
  }

  public async create(message: Message): Promise<Message> {
    return this.userService.getCurrentUser()
      .then(user => {
        message.createdBy = user;
        return axios.post(Urls.MESSAGES.CREATE, message)
          .then(response => new Message(response.data));
      });
  }

  public async update(message: Message): Promise<Message> {
    return axios.put(`${Urls.MESSAGES.UPDATE}${message.id}`, message)
      .then(response => new Message(response.data));
  }

  public async delete(message: Message): Promise<boolean> {
    return axios.delete(`${Urls.MESSAGES.DELETE}${message.id}`)
      .then(response => response.status === 200);
  }
}
