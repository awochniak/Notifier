package com.example.arkadiuszwochniak.notifier;

public class Post {
    private String title, status;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public Post(String title, String status) {
        this.title = title;
        this.status = status;
    }

    public Post(){

    }
}
