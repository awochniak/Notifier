package com.example.arkadiuszwochniak.notifier;

public class Post {
    private String title;
    private String message;
    private Boolean status;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public Boolean getStatus() {
        return status;
    }

    public void setStatus(Boolean status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Post(String title, String message, Boolean status) {
        this.title = title;
        this.message = message;
        this.status = status;
    }

    public Post(){

    }
}
