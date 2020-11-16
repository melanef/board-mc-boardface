export interface UserBody {
  id: number,
  name: string,
  email: string,
}

export default class User {
  private _id: number;
  private readonly _name: string;
  private readonly _email: string;

  public constructor(body: UserBody) {
    this._id = body.id;
    this._name = body.name;
    this._email = body.email;
  }

  public get name() {
    return this._name;
  }

  public get email() {
    return this._email;
  }
}
