import User, {UserBody} from "./User";

export interface MessageBody {
  id: number,
  subject: string,
  content: string,
  createdBy: UserBody,
  startDate: {date: string},
  expirationDate: {date: string},
}

export default class Message {
  private readonly _id: number | null;
  private _subject: string;
  private _content: string;
  private _createdBy: User | null;
  private _startDate: Date | null;
  private _expirationDate: Date | null;

  public constructor(body?: MessageBody) {
    this._id = null;
    this._subject = '';
    this._content = '';
    this._createdBy = null;
    this._startDate = null;
    this._expirationDate = null;

    if (body) {
      this._id = body.id;
      this._subject = body.subject;
      this._content = body.content;
      this._createdBy = new User(body.createdBy);
      this._startDate = new Date(body.startDate.date);

      if (!!body.expirationDate) {
        this._expirationDate = new Date(body.expirationDate.date);
      }
    }
  }

  public get id(): number | null {
    return this._id;
  }

  public get subject(): string {
    return this._subject;
  }

  public set subject(subject: string) {
    this._subject = subject;
  }

  public get content(): string {
    return this._content;
  }

  public set content(content: string) {
    this._content = content;
  }

  public get createdBy(): User | null {
    return this._createdBy;
  }

  public set createdBy(user: User | null) {
    if (!user) {
      return;
    }

    this._createdBy = user;
  }

  public get startDate(): Date | null {
    return this._startDate;
  }

  public set startDate(date: Date | null) {
    if (!date) {
      return;
    }

    this._startDate = date;
  }

  public get expirationDate(): Date | null {
    return this._expirationDate;
  }

  public set expirationDate(date: Date | null) {
    if (!date) {
      return;
    }

    this._expirationDate = date;
  }

}
