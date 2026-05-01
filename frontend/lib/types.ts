export type Role = 'admin' | 'voter';

export interface User {
  id: number;
  name: string;
  email: string;
  role: Role;
  email_verified_at: string | null;
}

export interface Poll {
  id: number;
  user_id: number;
  title: string;
  description: string | null;
  is_active: boolean;
  allow_multiple: boolean;
  starts_at: string | null;
  ends_at: string | null;
  options_count?: number;
  votes_count?: number;
  created_at: string;
  updated_at: string;
  options?: Option[];
}

export interface Option {
  id: number;
  poll_id: number;
  text: string;
  order: number;
  votes_count?: number;
  percentage?: number;
  created_at: string;
  updated_at: string;
}

export interface Vote {
  id: number;
  user_id: number;
  poll_id: number;
  option_id: number;
  created_at: string;
  updated_at: string;
  poll?: Poll;
  option?: Option;
}

export interface ApiResponse<T> {
  data: T;
  message?: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}
