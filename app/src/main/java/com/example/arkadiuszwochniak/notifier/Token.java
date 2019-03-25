package com.example.arkadiuszwochniak.notifier;

public class Token {
    private String title;
    private Boolean status;

    public Token(String title, Boolean status) {
        this.title = title;
        this.status = status;
    }

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

    public Token(){

    }
}
